<?php 
namespace  Modules\Ballots\Data\Tables;
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
    
    public function getBallotById( $id ) { }
    public function getResultByBallotId($id) { }
    public function castVoteByBallotId(VoteModel $vote, $id) { }
}
