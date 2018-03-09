<?php
namespace Modules\Ballots\Data\Model;
class Vote
{
     public $id;
     public $ballot_id;
     public $abstain = true;
     public $yea = false;
     public $nea = false;
}
