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
        debug($_GET,$_POST);
        if(isset($_POST['process']) && $_POST['process'] === 'Create-Ballot')
        {
            //debug($_POST);
            $this->valid = [false, false];
            //lets validate what we need to!
            if(isset($_POST['name']) && $_POST['name']) {
                 if(preg_match ('/^[a-zA-Z0-9 \-]+$/', $_POST['name'])) {
                    $name = $_POST['name'];
                     $this->valid[0] = true;
                 } else {
                    $this->valid[0] = false;
                 }
            }
            if(isset($_POST['description']) && $_POST['description']) {
                 if(preg_match ('/^[a-zA-Z0-9 \-!,.]+$/', $_POST['description'])) {
                    $description = $_POST['description'];
                     $this->valid[1] = true;
                 } else {
                    $this->valid[1] = false;
                 }
            }
            if($this->valid[0] && $this->valid[1]) {
                $this->newBallet = $this->getModel('Ballot','Ballots');
                $this->newBallet->name = $name;
                $this->newBallet->description = $description;
                if(isset($_REQUEST['output']) && strtolower($_REQUEST['output']) === 'json') {
                    $this->setNoRenderView();
                    header('Content-Type: application/json');
                    echo json_encode($this->getTable('Ballots','Ballots')->createBallot($this->newBallet));
                } else {
                    $this->newBalletId = $this->getTable('Ballots','Ballots')->createBallot($this->newBallet);
                }
            } else {
                if(isset($_REQUEST['output']) && strtolower($_REQUEST['output']) === 'json') {
                    $this->setNoRenderView();
                    header('Content-Type: application/json');
                    echo json_encode([$valid, $newBallet]);
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
                $this->newVote = $this->getModel('Vote','Ballots');
                switch(strtolower($_POST['vote'])) {
                     case 'nea':
                        $this->newVote->abstain = false;
                        $this->newVote->yea = false;
                        $this->newVote->nea = true;                    
                        break;
                     case 'yea':
                        $this->newVote->abstain = false;
                        $this->newVote->yea = false;
                        $this->newVote->nea = true;
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
                echo json_encode($this->getTable('Votes','Ballots')->castVoteByBallotId($this->newVote, $ballot_id));
            } else {
                $this->newVoteId = $this->getTable('Votes','Ballots')->castVoteByBallotId($this->newVote, $ballot_id);
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
            echo json_encode($this->getTable('Ballots','Ballots')->getResultByBallotId((int)$this->getRequest()->getParam('ballot_id')));
        } else {
            $this->results = $this->getTable('Ballots','Ballots')->getResultByBallotId((int)$this->getRequest()->getParam('ballot_id'));
        }    
    }
}
