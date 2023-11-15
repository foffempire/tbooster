<?php  

	/**
	 * 
	 */
	class User 
	{
		private $kon;
		private $email;

		function __construct($kon, $email)
		{
			$this->kon = $kon;
			$this->email =$email;

			$stmt = $this->kon->prepare("SELECT * FROM users WHERE email = :em ");
			$stmt->bindParam("em", $this->email);
			$stmt->execute();

			$this->data = $stmt->fetch(PDO::FETCH_ASSOC);
		}


		public function id(){
			return $this->data['id'];
		}
		public function password(){
			return $this->data['password'];
		}
		public function fullname(){
			return $this->data['fullname'];
		}
		public function acctNo(){
			return $this->data['acct_no'];
		}
		public function bank(){			
			return $this->data['bank'];
		}		
		public function email(){			
			return $this->data['email'];
		}		
		public function regDate(){
			return $this->data['date_added'];
		}
		public function status(){
			return $this->data['status'];
		}
		public function schedule(){
			return $this->data['schedule'];
		}
		public function referral(){
			return $this->data['referral_link'];
		}
		public function referrer(){
			return $this->data['referrer'];
		}


		function totalUnpaid($uid){
			$stmt = $this->kon->prepare("SELECT SUM(price) AS sum FROM completed WHERE user_id = :uid AND status = 1 AND payout_requested = 0 AND is_paid = 0 ");
			$stmt->bindParam(":uid", $uid);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row['sum'];
		}

		function canCashOut($uid){
			if($this->totalUnpaid($uid) >= 1000){
				return true;
			}
		}
		function totalPendingPayment($uid){
			$stmt = $this->kon->prepare("SELECT SUM(price) AS sum FROM completed WHERE user_id = :uid AND status = 1 AND payout_requested = 1 AND is_paid = 0 ");
			$stmt->bindParam(":uid", $uid);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row['sum'];
		}
		function totalPaid($uid){
			$stmt = $this->kon->prepare("SELECT SUM(price) AS sum FROM completed WHERE user_id = :uid AND is_paid = 1 ");
			$stmt->bindParam(":uid", $uid);
			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			return $row['sum'];
		}

		function setUnpaidAsPending($uid){
			$stmt = $this->kon->prepare("UPDATE completed SET payout_requested = 1 WHERE user_id = :uid AND status = 1 AND payout_requested = 0 AND is_paid = 0 ");
			$stmt->bindParam(":uid", $uid);
			return $stmt->execute();
		}
		

		function countReferred(){
			$ref = $this->referral();
			$stmt = $this->kon->prepare("SELECT * FROM users WHERE referrer = :refl AND ref_paid = 0");
			$stmt->bindParam(":refl",$ref);
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $stmt->rowCount();
		}


		public function updateBank($fname, $bank, $acctno){
			$stmt = $this->kon->prepare("UPDATE users SET fullname = :fn, bank = :ln, acct_no = :mn WHERE email = :em ");
			$stmt->bindParam(":fn", $fname);
			$stmt->bindParam(":ln", $bank);
			$stmt->bindParam(":mn", $acctno);
			$stmt->bindParam(":em", $this->email);
			return $stmt->execute();
		}


		function verifyPassword($oldPassword){
			if(md5($oldPassword) === $this->password()){
				return true;				
			}
		}

		public function updatePassword($newPassword){
			$pass = md5($newPassword);
			$stmt = $this->kon->prepare("UPDATE users SET password = :pw WHERE email = :em ");
			$stmt->bindParam(":pw", $pass);
			$stmt->bindParam(":em", $this->email);
			return $stmt->execute();
		}

	}

?>