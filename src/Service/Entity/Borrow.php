<?php
namespace App\Service\Entity;
use App\Service\Entity as EntityService;
use App\Entity\Borrow as BorrowEntity;

/**
 * Attention! This could be confusing with the Borrow entity class placed into "src/Entity" folder
 *
 * @author fabriced
 *        
 */
class Borrow extends EntityService{

	/**
	 * Perform a full check
	 *
	 * @param BorrowEntity $borrowEntity
	 *        	Object to be checked
	 * @return boolean
	 */
	function fullCheck(BorrowEntity $borrowEntity){
		if (!$this->checkExpiryDateValidity($borrowEntity))
			return false;
		return true;
	}

	/**
	 * This will compare (borrow date + borrow duration) with expiry date.
	 * Note that, for borrow creation (new), expiry date is set on "prepersist" (look at "Borrow" entity class)
	 *
	 * @param BorrowEntity $borrowEntity
	 * @return boolean
	 */
	function checkExpiryDateValidity(BorrowEntity $borrowEntity){
		$borrowDuration = $borrowEntity->getBorrowDuration();
		$borrowDate = $borrowEntity->getBorrowDate();
		$expiryDate = $borrowEntity->getExpiryDate();
		// Calculate expected expiry date
		$expectedExpiryDate = new \DateTime(date('Y-m-d', $borrowDate->getTimestamp()));
		$expectedExpiryDate->add('P' . $borrowDuration . 'D');
		if (!$expiryDate){
			$borrowEntity->setExpiryDate($expectedExpiryDate);
			return true;
		}
		if (date('Y-m-d', $expiryDate->getTimestamp()) != $expectedExpiryDate->format('Y-m-d'))
			return false;
		return true;
	}
}