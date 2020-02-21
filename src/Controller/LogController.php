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

        if ($this->Log->find()->where(['id' => $log->id])->first() !== null) {

            return $this->getResponse()
                ->withStatus(409)
                ->withStringBody(json_encode((object) [
                    "id" => $log->id
                ]));

        }

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

        if (!$this->Log->save($log)) {

            return $this->getResponse()->withStatus(500);

        }


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

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        $ids = [];
        foreach ($rows as $row) {

            $ids[] = $row['id'];

        }

        $exists = $this->Log->find()->where(['id IN' => $ids])->all()->toArray();

        if (count($exists) > 0) {

            $bad_ids = [];
            foreach ($exists as $log) {

                $bad_ids[] = $log->id;

            }

            return $this->getResponse()
                ->withStatus(409)
                ->withStringBody(json_encode((object) [
                    "ids" => $bad_ids
                ]));

        }


        $log = [];
        foreach ($rows as $row) {

            $log[] = $this->Log->newEntity($row);

        }

        if (!$this->Log->saveMany($log)) {

            return $this->getResponse()->withStatus(500);

        }

        return $this->getResponse()->withStatus(201);

    }

}
