<?php

namespace Cogitoweb\TelemetryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TelemetryController extends Controller
{
    public function listAction()
    {
        $connection = $this->getDoctrine()->getConnection();
        $query = "select id, name from cogitoweb_telemetryview_bundle where active = true";
           
        $stmt = $connection->executeQuery($query, []);
        
        $response = new JsonResponse();
        $response->setData(array('result' => 'OK', 'message' => '', 'data' => $stmt->fetchAll()));
        
        return $response;
    }
    
    public function exportAction($id)
    {
        $connection = $this->getDoctrine()->getConnection();
        $query = "select id, name, sql from cogitoweb_telemetryview_bundle where id = :id";
           
        $stmt = $connection->executeQuery($query, ['id' => $id]);
        $view = $stmt->fetch();
        
        if(!count($view)) {
            throw new NotFoundHttpException();
        }
        
        $sql = $view["sql"];
        $forbidden_words = array('update', 'delete', 'insert', 'drop', 'create', 'truncate');
        
        foreach($forbidden_words as $f) {
            
            if(stripos($sql, $f.' ') !== false)
            {
                throw new \Excption('attempt to use forbidden word in query: forbbiden words are '.implode(',', $forbidden_words));
            }
        }
        
        $result = 'OK';
        $message = '';
        $data = null;
        
        try {
            
            $stmt = $connection->executeQuery($sql);
            $data = array(
                'id' => $view['id'],
                'name' => $view['name'],
                'rows' => $stmt->fetchAll()
            );
 
        } catch (\Exception $ex) {
            $result = 'KO';
            $message = $ex->getMessage();
        }

        $response = new JsonResponse();
        // optional jsonp
        if($this->getRequest()->get('callback'))$response->setCallback($this->getRequest()->get('callback'));
        $response->setData(array('result' => $result, 'message' => $message, 'data' => $data));
        $response->setData(array('result' => $result, 'message' => $message, 'data' => $data));
        
        return $response;
    }
}
