<?php
namespace Log\Controller;


use Cake\I18n\FrozenTime;
use PDO;

/**
 * Log Controller
 *
 * @property \Log\Model\Table\LogTable $Log
 */
class LogController extends AppController
{

    const tableName = 'log';


    public function add () {

        if (!$this->getRequest()->is('post')) {

            return $this->getResponse()->withStatus(405);

        }

        $params = $this->getRequest()->getData();

        $log = $this->Log->newEntity();
        $this->Log->patchEntity($log, $params);

        if (gettype($this->getRequest()->getData('logEventDate')) === 'integer') {

            $log->logEventDate = date_timestamp_set(new \DateTime, $params['logEventDate']);

        } else {

            $log->setError('logEventDate', ['integer' => 'The provided value is invalid'], true);

        }

        if (count($log->getErrors()) > 0) {

            return $this->getResponse()
                ->withStatus(400)
                ->withStringBody(json_encode($log->getErrors()));

        }

        $this->Log->save($log);

        return $this->getResponse()->withStatus(201);

    }

    public function upload () {

        if (!$this->getRequest()->is('post')) {

            return $this->getResponse()->withStatus(405);

        }

        $params = $this->getRequest()->getData();

        if (!isset($params['database'])) {

            return $this->getResponse()
                ->withStatus(400)
                ->withStringBody(json_encode((object) [

                    "database" => [

                        "_required" => "This field is required"

                    ]

                ]));

        }

        if (!isset($params['database']['tmp_name'])) {

            return $this->getResponse()
                ->withStatus(400)
                ->withStringBody(json_encode((object) [

                    "database" => [

                        "file" => "The provided value is invalid"

                    ]

                ]));

        }


        move_uploaded_file($params['database']['tmp_name'], WWW_ROOT . '/temp.sqlite');

        $connection = new PDO('sqlite:' . WWW_ROOT . '/temp.sqlite');

        $query = $connection->query('SELECT * FROM '. $this::tableName .';');
        $success = $query->execute();

        if (!$success) {

            return $this->getResponse()
                ->withStatus(400)
                ->withStringBody(json_encode((object) [

                    "database" => [

                        "file" => "Invalid database"

                    ]

                ]));

        }

        $log = [];
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $row) {

            $log[] = $this->Log->newEntity($row);

        }

        if (!$this->Log->saveMany($log)) {

            return $this->getResponse()->withStatus(500);

        }

        return $this->getResponse()->withStatus(201);

    }

}
