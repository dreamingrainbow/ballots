<?php
namespace Modules\Primary\Controllers;
use \Library\Controller;
class Pages extends Controller
{
    public function home()
    {
        if(isset($_REQUEST['limit']) && $_REQUEST['limit']) {
            if(preg_match ('/^[0-9]+$/', $_REQUEST['limit'])) {
                $limit = $_REQUEST['limit'];
            } else {
                $limit = 10;        
            }
        } else {
            $limit = 10;
        }
        
        if(isset($_REQUEST['offset']) && $_REQUEST['offset']) {
            if(preg_match ('/^[0-9]+$/', $_REQUEST['offset'])) {
                $offset = $_REQUEST['offset'];
            } else {
                $offset = 10;        
            }
        } else {
            $offset = 0;
        }
        
        if(isset($_REQUEST['output']) && $_REQUEST['output'] === 'json') {
            $this->setNoRenderView();
            header('Content-Type: application/json');
            echo json_encode([ballots => $this->getTable('Ballots','Ballots')->getBallots($limit, $offset)]);
        } else {
            $this->ballots = $this->getTable('Ballots','Ballots')->getBallots($limit, $offset);
        }
    }
}

