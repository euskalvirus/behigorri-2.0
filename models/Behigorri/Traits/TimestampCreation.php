<?php

namespace Behigorri\Traits;

/**
*
* @author Alain Zabaleta <alayn at barnetik dot com>
*/
trait TimestampCreation
{
   /**
    * @ORM\PrePersist
    */
   public function setCurrentDateOnPersist()
   {
       $this->createdAt = \Carbon\Carbon::now();
       $this->updatedAt = \Carbon\Carbon::now();
   }

   /**
    * @ORM\PreUpdate
    */
   public function setCurrentDateOnUpdate()
   {
       $this->updatedAt = \Carbon\Carbon::now();
   }

}