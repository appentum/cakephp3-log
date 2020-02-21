<?php
namespace Log\Command;

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionInterface;
use Cake\Datasource\ConnectionManager;
use \Exception;

/**
 * Log command.
 */
class LogCommand extends Command
{

    const tableName = 'logger_log';

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/3.0/en/console-and-shells/commands.html#defining-arguments-and-options
     *
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser)
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

    /**
     * Implement this method with your command's logic.
     *
     * @param Arguments $args The command arguments.
     * @param ConsoleIo $io The console io
     *
     * @throws Exception
     *
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {

        $arg = $args->getArgumentAt(0);


        switch ($arg) {

            case 'initialize':
                $this->initalizeCommand();
                break;

            default:
                $this->helpCommand($io);
                break;

        }

        return null;

    }


    protected function initalizeCommand () {

        $connection = ConnectionManager::get('default');

        if ($this->logTableExists($connection)) {

            throw new Exception('Table with the name ['.$this::tableName.'] already exists!');

        }

        $this->createLogTable($connection);

        return null;

    }

    protected function helpCommand (ConsoleIo $io) {

        $io->info("@appentum/log\n");

        $io->info("Usage:\n\n  bin/cake log \e[0;32mcommand\e[0m\n\n\n    Available commands:\n\n    \e[0;32minitialize\e[0m\t\tInitializes database\n    \e[0;32mhelp\e[0m\t\tShow this message");

    }

    /**
     * Ellenőrzi, hogy létezik-e log nevű tábla
     * @param ConnectionInterface $connection
     *
     * @throws Exception
     *
     * @return bool
     */
    protected function logTableExists (ConnectionInterface $connection) {

        try {

            $tables = $connection->execute('SHOW TABLES');

        } catch (Exception $exception) {

           throw new Exception ('Couldn\'t connect to the database, please check your [config/app.php] file!');

        }

        foreach ($tables->fetchAll() as $table) {

            if (count($table) > 0 && $table[0] === $this::tableName) {

                return true;

            }

        }

        return false;

    }

    /**
     * Létrehozza a log táblát
     *
     * @param ConnectionInterface $connection
     *
     * @throws Exception
     *
     * @return void
     */
    protected function createLogTable (ConnectionInterface $connection) {

        try {

            $connection->execute('
                CREATE TABLE '.$this::tableName.' (
                    id VARCHAR(128) PRIMARY KEY,
                    uniqueId VARCHAR(255) NOT NULL,
                    sessionId VARCHAR(255) NOT NULL,
                    title VARCHAR(255) NOT NULL,
                    text MEDIUMTEXT NOT NULL,
                    logEventDate DATETIME NOT NULL,
                    logType INT(11) NOT NULL,
                    applicationVersion VARCHAR(255) NOT NULL,
                    deviceId VARCHAR(255) NOT NULL
                )
            ');

        } catch (Exception $exception) {

            throw new Exception('Couldn\'t create table with the name ['.$this::tableName.']!');

        }

    }

}
