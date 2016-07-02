<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace BaseBundle\Controller;

use BaseBundle\Library\DatabaseEntryInterface;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use BaseBundle\Library\ViewData;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractWorkflowController extends ApiController {

    abstract public function getWorkflow(): DatabaseWorkflow;

    /**
     * @return View
     */
    public function handleGetOne($id) {

        $workflow = $this->getWorkflow();

        try{
            $entity = $workflow->get($id);
        }catch (EntityNotFoundException $e){
            return $this->handleNotFound($e->getMessage());
        }

        return $this->prepareView(new ViewData(Codes::HTTP_OK, $entity));
    }

    /**
     * @param $id
     * @return \FOS\RestBundle\View\View
     */
    public function handleDelete(int $id) {

        try{
            $entity = $this->getWorkflow()->get($id);
        }catch (EntityNotFoundException $e){
            return $this->handleNotFound($e->getMessage());
        }

        $em = $this->getDoctrine()->getManager();

        $em->remove($entity);
        $em->flush();

        return $this->handleSuccess(sprintf('Dataset successfully removed (id: %d)', $id));
    }

    /**
     * @param DatabaseEntryInterface $entry
     * @return View
     */
    public function handleCreate(DatabaseEntryInterface $entry){

        $callback = function(DatabaseWorkflow $workflow, DatabaseEntryInterface $entry) {
            return $workflow->create($workflow->prepareEntity($entry));
        };

        return $this->process($entry, $callback , 'Dataset unsuccessfully updated');
    }

    /**
     * @param DatabaseEntryInterface $entry
     * @return View
     */
    public function handleUpdate(DatabaseEntryInterface $entry): View {

        $callback = function(DatabaseWorkflow $workflow, DatabaseEntryInterface $entry) {
            return $workflow->update($workflow->prepareEntity($entry));
        };

        return $this->process($entry, $callback , 'Dataset unsuccessfully updated');
    }

    /**
     * @param $paramFetcher
     * @return View
     */
    public function handleFindAll($paramFetcher) {

        $callback = function($workflow, $offset, $limit, $queryParam) {
            return $workflow->findAll($offset, $limit, $queryParam);
        };

        return $this->getWrapper($callback, $paramFetcher);
    }

    /**
     * @param DatabaseEntryInterface $entry
     * @param callable $callback
     * @param string $message
     * @return View
     */
    private function process(DatabaseEntryInterface $entry, callable $callback, string $message): View {

        $workflow = $this->getWorkflow();

        try{
            /**
             * @var DatabaseWorkflowEntityInterface $entity
             */
            $entity = $callback($workflow, $entry);
        }catch (\Exception $e){
            return $this->handleError(Codes::HTTP_BAD_REQUEST, vsprintf('%s (id: %s)', [$message, $entry->getIdentifier()]));
        }

        return $this->handleSuccess(vsprintf('%s (id: %s)', [$message, $entity->getIdentifier()]));
    }

    /**
     * @param callable $callback
     * @param ParamFetcher $paramFetcher
     * @param null $queryParam
     * @throws NoResultException | \Exception
     * @return View
     */
    public function getWrapper(callable $callback, ParamFetcher $paramFetcher, $queryParam = null): View {

        $limit = $paramFetcher->get('limit');
        $offset = $paramFetcher->get('offset');

        try{
            $entities = $callback($this->getWorkflow(), $offset, $limit, $queryParam);
        }catch (NoResultException $e) {
            $this->handleNotFound($e->getMessage());
        }catch( \Exception $e){
            $this->handleError(Codes::HTTP_BAD_REQUEST, $e->getMessage());
        }

        return $this->prepareView(new ViewData(Codes::HTTP_OK, $entities, array('offset' => $offset, 'limit' => $limit)));
    }
}