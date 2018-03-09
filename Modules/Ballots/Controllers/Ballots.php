<?php
namespace Modules\Ballots\Controllers;
use \Library\Controller;
class Ballots extends Controller
{
    /*
    *   Search for Ballots 
    */    
    public function search(){
        if($_POST['q']) {
            $q = $_POST['q'];
            if($_POST['f']) {
                $filters = [];
                switch(strtolower($_POST['f'])) {                        
                    case 'name':
                        array_push($filters, 'name');
                        break;   
                    case 'description':
                        array_push($filters, 'description');
                        break;                        
                    case 'id':
                    default:
                        array_push($filters, 'id');
                        break;
                }
            } else {
                $filter = null;
            }
            $this->query = $this->getTable('Ballots','Ballots')->getSearchResults($q, $filters);
        }
    }
    
    /*
    *   Add A Ballot
    */
    public function add() {
        if(isset($_POST['process']) && $_POST['process'] === 'Create-Ballot')
        {
            //debug($_POST);
            $valid = [false, false];
            //lets validate what we need to!
            if(isset($_POST['name']) && $_POST['name']) {
                 if(preg_match ('/^[a-zA-Z0-9 \-]+$/', $_POST['name'])) {
                    $name = $_POST['name'];
                     $valid[0] = true;
                 } else {
                    $valid[0] = false;
                 }
            }
            if(isset($_POST['description']) && $_POST['description']) {
                 if(preg_match ('/^[a-zA-Z0-9 \-!,.]+$/', $_POST['description'])) {
                    $description = $_POST['description'];
                     $valid[1] = true;
                 } else {
                    $valid[1] = false;
                 }
            }
            if($valid[0] && $valid[1]) {
                $this->view->newBallet = $this->getModel('Ballot','Ballots');
                $this->view->newBallet->name = $name;
                $this->view->newBallet->description = $description;
                if(isset($_REQUEST['output']) && strtolower($_REQUEST['output']) === 'json') {
                    $this->setNoRenderView();
                    header('Content-Type: application/json');
                    echo json_encode($this->getTable('Ballots','Ballots')->createBallot($this->view->newBallet));
                } else {
                    $this->view->newBalletId = $this->getTable('Ballots','Ballots')->createBallot($this->view->newBallet);
                }
            } else {
                if(isset($_REQUEST['output']) && strtolower($_REQUEST['output']) === 'json') {
                    $this->setNoRenderView();
                    header('Content-Type: application/json');
                    echo json_encode([$valid, $newBallet]);
                } else {
                    $this->view->valid = $valid;
                }
            }
        }
    }
    
    /*
    *   Cast a vote for a Ballot
    */
    public function cast() {
        $ballot_id = $this->getRequest()->getParam('ballot_id');
        if($ballot_id) {
            return $this->fileNotFound('Ballot Id was missing, and ballot could not be found.');
        }
        if(isset($_POST['process']) && $_POST['process'] === 'Cast-Ballot')
        {            
            //lets validate what we need to!
            if(isset($_REQUEST['vote']) && $_POST['vote']) {
                $this->view->newVote = $this->getModel('Vote','Ballots');
                switch(strtolower($_POST['vote'])) {
                     case 'nea':
                        $this->view->newVote->abstain = false;
                        $this->view->newVote->yea = false;
                        $this->view->newVote->nea = true;                    
                        break;
                     case 'yea':
                        $this->view->newVote->abstain = false;
                        $this->view->newVote->yea = false;
                        $this->view->newVote->nea = true;
                        break;
                     case 'abstain':
                     default:
                        /* Dont need to do anyting its automatic...*/
                        break;
                 }
            }
            if(isset($_REQUEST['output']) && strtolower($_REQUEST['output']) === 'json') {
                $this->setNoRenderView();
                header('Content-Type: application/json');
                echo json_encode($this->getTable('Votes','Ballots')->castVoteByBallotId($this->view->newVote, $ballot_id));
            } else {
                $this->view->newVoteId = $this->getTable('Votes','Ballots')->castVoteByBallotId($this->view->newVote, $ballot_id);
            }
        }
    }
    
    /*
    *   Search for Ballots 
    */
    public function results() {
        if(isset($_REQUEST['output']) && strtolower($_REQUEST['output']) === 'json') {
            $this->setNoRenderView();
            header('Content-Type: application/json');
            echo json_encode($this->getTable('Ballots','Ballots')->getResultByBallotId($this->getRequest()->getParam('ballot_id')));
        } else {
            $this->view->results = $this->getTable('Ballots','Ballots')->getResultByBallotId($this->getRequest()->getParam('ballot_id'));
        }    
    }
}
