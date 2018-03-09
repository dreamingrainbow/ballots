<?php 
namespace  Modules\Ballots\Data\Tables;
use PDO;
use PDOException;
use Library\Data\Pdo\MySql\Table;
use Module\Ballots\Data\Model\Ballot as BallotModel;
use Module\Ballots\Data\Model\Vote as VoteModel;
class Ballots extends Table
{
    protected $_name = 'Ballots';
    
    public function createBallot( BallotModel $ballot)
    {
        $ballotId = false;
        try
        {
            $stmt = $this->connection()->prepare("INSERT INTO Ballots set subject=?");
            try
            {
                $this->connection()->beginTransaction();
                $stmt->execute([$ballot->subject]);
                $ballotId = $this.connection()->lastInsertId();
                $this->connection()->commit();
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
    
    public function getBallotById( $id ) { 
        $sql = 'SELECT Ballots.* FROM Ballots';
        $sql .= ' WHERE Ballots.id = ?';        
        $sth = $this->connection()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);	
        $sth->execute([$id]);	
        return $sth->fetch(PDO::FETCH_ASSOC);  
    }
    
    public function getResultByBallotId($id) { 
        $sql = 'SELECT Ballots.name, Ballots.description, Votes.*, 
        (SELECT count(Votes.abstain) as YeaCount FROM Votes where abstain="true" and ballot_id=?) as AbstainCount, 
        (SELECT count(Votes.yea) as YeaCount FROM Votes yea="true" and ballot_id=?) as YeaCount, 
        (SELECT count(Votes.nea) as YeaCount FROM Votes nea="true" and ballot_id=?) as NeaCount, 
        FROM Votes';
        $sql .= ' INNER JOIN Ballots on (Votes.ballot_id = Ballots.id)';
        $sql .= ' WHERE Ballots.id = ?';        
        $sth = $this->connection()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);	
        $sth->execute([$id,$id,$id,$id]);	
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getYeaVotesByBallotId($id) {
        $sql = 'SELECT Ballots.name, count(Votes.yea) as YeaCount FROM Votes';	
        $sql .= ' INNER JOIN Ballots on (Votes.ballot_id = Ballots.id)';
        $sql .= 'WHERE Ballots.id = ?';
        $sql . = 'and Votes.yea = "true"';       
        $sth = $this->connection()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);	
        $sth->execute([$id]);	
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getNeaVotesByBallotId($id) {
        $sql = 'SELECT Ballots.name, count(Votes.yea) as YeaCount FROM Votes';	
        $sql .= ' INNER JOIN Ballots on (Votes.ballot_id = Ballots.id)';
        $sql .= 'WHERE Ballots.id = ?';
        $sql . = 'and Votes.nea = "true"';       
        $sth = $this->connection()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);	
        $sth->execute([$id]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getYeaVotesByBallotId($id) {
        $sql = 'SELECT Ballots.name, count(Votes.yea) as YeaCount FROM Votes';	
        $sql .= ' INNER JOIN Ballots on (Votes.ballot_id = Ballots.id)';
        $sql .= 'WHERE Ballots.id = ?';
        $sql . = 'and Votes.yea = "true"';       
        $sth = $this->connection()->prepare($sql, [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);	
        $sth->execute([$id]);	
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function castVoteByBallotId(VoteModel $vote, $id) {
        $ballotId = false;
        try
        {
            $stmt = $this->connection()->prepare("INSERT INTO Ballots set subject=?");
            try
            {
                $this->connection()->beginTransaction();
                $stmt->execute([$ballot->subject]);
                $ballotId = $this.connection()->lastInsertId();
                $this->connection()->commit();
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
