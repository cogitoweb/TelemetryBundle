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
        $data = [];
        
        try {
            
            $stmt = $connection->executeQuery($sql);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            for($i = 1; $i<10; $i++)
            {

                // popolamento assi
                $xaxis = array();
                $yaxis = array();
                $ylabel = "y label";
                $zaxis = array();
                $lat = array();
                $lon = array();
                
                foreach($rows as $r) {

                    if(!isset($r['x'])) {
                        throw new \Exception('x axis not found');
                    }

                    $xaxis[] = (isset($r['x'])) ? $r['x'] : $r[0];
                    
                    if(isset($r['y'.$i])) 
                    {
                        $yaxis[] = $r['y'.$i];
                    }
                    if(isset($r['y'.$i.'label'])) 
                    {
                        $ylabel = $r['y'.$i.'label'];
                    }
                    if(isset($r['z'.$i]))
                    {
                        $zaxis[] = $r['z'.$i];
                    }

                    if(isset($r['lat'.$i]))
                    {
                        $lat[] = $r['lat'.$i];
                    }
                    if(isset($r['lon'.$i]))
                    {
                        $lon[] = $r['lon'.$i];
                    }

                }
                
                $data[] = array(
                    'x' => $xaxis,
                    'y' => $yaxis,
                    'name' => $ylabel,
                    'z' => $zaxis,
                    'lat' => $lat,
                    'lon' => $lon
                );
                    
            }
 
        } catch (\Exception $ex) {
            $result = 'KO';
            $message = $ex->getMessage();
        }

        $response = new JsonResponse();
        // optional jsonp
        if($this->getRequest()->get('callback'))$response->setCallback($this->getRequest()->get('callback'));
        $response->setData(array('result' => $result, 'message' => $message, 'data' => $data, 'view' => $view));
        
        return $response;
    }
}
