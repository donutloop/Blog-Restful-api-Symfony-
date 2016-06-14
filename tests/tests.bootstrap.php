<?php

function runCommand($command){
    passthru($command, $result);

    printf(">>> %s%s", $command, PHP_EOL);

    if ($result > 0) {
        fwrite(STDERR, vsprintf('%s returned with %s \n', array($command, $result)));
        exit(1);
    }
}

$rootDir = dirname(dirname( __FILE__));

runCommand(sprintf('php "%s/bin/console" cache:clear --no-warmup -env=test', $rootDir));
runCommand(sprintf('php "%s/bin/console" doctrine:database:drop --env=test --force --if-exists', $rootDir));
runCommand(sprintf('php "%s/bin/console" doctrine:database:create --env=test', $rootDir));
runCommand(sprintf('php "%s/bin/console" doctrine:schema:update --force --env=test', $rootDir));

require $rootDir . '/app/autoload.php';

