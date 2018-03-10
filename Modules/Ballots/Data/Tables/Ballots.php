<?php 
namespace  Modules\Ballots\Data\Tables;
use PDO;
use PDOException;
use Library\Data\Pdo\MySql\Table;
use Modules\Ballots\Data\Model\Ballot as BallotModel;
use Modules\Ballots\Data\Model\Vote as VoteModel;
class Ballots extends Table
{
    protected $_name = 'Ballots';
    
    public function createBallot( BallotModel $ballot)
    {
        $ballotId = false;
        try
        {
            $stmt = $this->connection()->prepare("INSERT INTO Ballots set name=?, description=?");
            try
            {
                $this->connection()->beginTransaction();
                $stmt->execute([$ballot->name, $ballot->description]);
                $ballotId = $this->connection()->lastInsertId();
                $this->connection()->commit();
                return $ballotId;
            }
            catch(PDOExecption $e) {
                $this->connection->rollback();
                print "Error!:". $e->getMessage() . "</br>";
            }
        }
        catch(PDOExeception $e)
        {
            print "Error!:". $e->getMessage() . "</br>";
        }
    }
    
    public function getBallots( $limit = 10, $offset = 0 ) { 
        $sql = 'SELECT Ballots.* FROM Ballots';
        if($offset) {            
            $sql .= ' ORDER BY Ballots.id LIMIT ?,?';
        } else {
            $sql .= ' ORDER BY Ballots.id LIMIT ?';
        }
        
        $stmt = $this->connection()->prepare($sql);	
        if($offset) {            
            $stmt->bindParam(1, $offset, PDO::PARAM_INT);
            $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  
    }
    
    public function getBallotById( $id ) { 
        $sql = 'SELECT Ballots.* FROM Ballots';
        $sql .= ' WHERE Ballots.id = ?';        
        $sth = $this->connection()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);	
        $sth->execute([$id]);	
        return $sth->fetch(PDO::FETCH_ASSOC);  
    }
    
    public function getResultByBallotId($id) { 
        $sql = "SELECT Ballots.name, Ballots.description, Votes.*, ";
        $sql .= "(SELECT count(*) FROM Votes WHERE abstain='true' and ballot_id=?) as AbstainCount, ";
        $sql .= "(SELECT count(*) FROM Votes WHERE yea='true' and ballot_id=?) as YeaCount, ";
        $sql .= "(SELECT count(*) FROM Votes WHERE nea='true' and ballot_id=?) as NeaCount ";
        $sql .= "FROM Votes";
        $sql .= ' LEFT JOIN Ballots on (Votes.ballot_id = Ballots.id)';
        $sql .= ' WHERE Ballots.id = ?';  
        $sth = $this->connection()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
        $sth->bindParam(1, $id, PDO::PARAM_INT);
        $sth->bindParam(2, $id, PDO::PARAM_INT);
        $sth->bindParam(3, $id, PDO::PARAM_INT);
        $sth->bindParam(4, $id, PDO::PARAM_INT);
        $sth->execute();
        debug($id,$sth->debugDumpParams());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getYeaVotesByBallotId($id) {
        $sql = 'SELECT Ballots.name, count(Votes.yea) as YeaCount FROM Votes';	
        $sql .= ' INNER JOIN Ballots on (Votes.ballot_id = Ballots.id)';
        $sql .= 'WHERE Ballots.id = ?';
        $sql .= " and Votes.yea = true";       
        $sth = $this->connection()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);	
        $sth->execute([$id]);	
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getNeaVotesByBallotId($id) {
        $sql = 'SELECT Ballots.name, count(Votes.yea) as YeaCount FROM Votes';	
        $sql .= ' INNER JOIN Ballots on (Votes.ballot_id = Ballots.id)';
        $sql .= 'WHERE Ballots.id = ?';
        $sql .= " and Votes.nea = 'true'";       
        $sth = $this->connection()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);	
        $sth->execute([$id]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAbstainVotesByBallotId($id) {
        $sql = 'SELECT Ballots.name, count(Votes.yea) as YeaCount FROM Votes';	
        $sql .= ' INNER JOIN Ballots on (Votes.ballot_id = Ballots.id)';
        $sql .= 'WHERE Ballots.id = ?';
        $sql .= " and Votes.abstain = 'true'";       
        $sth = $this->connection()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);	
        $sth->execute([$id]);	
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function castVoteByBallotId(VoteModel $vote, $id) {
        $voteId = false;
        try
        {
            $stmt = $this->connection()->prepare("INSERT INTO Ballots set ballot_id=?, abstain=?, yea=?, nea=?");
            try
            {
                $this->connection()->beginTransaction();
                $stmt->execute([$vote->ballot_id, $vote->abstain, $vote->yea, $vote->nea]);
                $voteId = $this->connection()->lastInsertId();
                $this->connection()->commit();
                return $voteId;
            }
            catch(PDOExecption $e) {
                $this->connection->rollback();
                print "Error!:". $e->getMessage() . "</br>";
            }
        }
        catch(PDOExeception $e)
        {
            print "Error!:". $e->getMessage() . "</br>";
        }
    }
}
