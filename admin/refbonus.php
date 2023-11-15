<?php 
require_once "includes/init.php";
require_once "includes/functions.php";
define("PAGE", "refbonus");
if(!logged_in()){
    header("Location: login");
    exit();
}
$query = $kon->prepare("SELECT * FROM users ORDER BY id DESC");
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);
$n = 1;


if(isset($_GET['markpaid'])){
    $id = $_GET['markpaid'];
    $uza = new User($kon, $id);
    $done = $uza->setReferralAsPaid();
    if($done){
        Helper::redirect("refBonus");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="./assets/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="./assets/css/styles.css">
    <title>Pending Payments | Admin</title>
</head>
<body>
    <nav>
        <div class="header-container">
            <div class="header-content-left">
                <div class="header-left-item light-text hamburger">
                    <button class="transparent-btn open-menu"><i class="fas fa-bars"></i></button>
                    <button class="transparent-btn close-menu hidden"><i class="fas fa-times"></i></button>
                </div>
                <div class="header-left-item sm-d-none"><a href="#"><img src="./assets/img/logo.png" width="120"></a></div>
            </div>
            <div class="header-content-right">
                <div class="header-right-item">
                    <button class="transparent-btn notify-btn light-text"><i class="fas fa-bell"></i></button>
                    <div class="notification-dropdown hidden">
                        <p><i><small>No Notification</small></i></p>
                    </div>
                </div>
                <div class="margin-x-20 sm-d-none"></div>
                <div class="header-right-item sm-d-none">
                    <div class="trans_logo">AD</div>
                    <div class="nav_name">Admin</div>
                </div>
            </div>
        </div>
    </nav>
    <div class="notification-dropdown-bg hidden"></div>

    <div class="dashboard__main">
        <div class="dashboard__main__sidebar">
            <?php require_once "includes/components/sideMenu.php"; ?>
        </div>
        <div class="dashboard__main__content">
            <div class="dashboard__main__content__container">
                <div class="dashboard__main__content__row">
                    <div class="dashboard__main__content__row__item">
                        <h1 class="dashboard__main__content__pagetitle">Pending Referral Bonus</h1> 
                        <p class="dashboard__main__content__pagecaption light-text">
                            
                        </p>
                    </div>
                    <div class="settings__main__content__body">
                        <div class="dataset-data table-responsive">
                            <table id="example" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Names</th>
                                        <th>Bank</th>
                                        <th>Acct No</th>
                                        <th>Number of referrals</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php  foreach ($rows as $row) { 
                                        $user = new User($kon, $row['id']);
                                        if($user->countReferred()>0){
                                    ?>   
                                    <tr>
                                        <td><?= $n++ ?></td>
                                        <td><?= $user->fullname() ?></td>     
                                        <td><?= $user->bank() ?></td>     
                                        <td><?= $user->acctNo() ?></td>     
                                        <td><?= $user->countReferred() ?></td>     
                                        <td>&#8358;<?= $user->countReferred()*refBonus() ?></td>     
                                        <td>
                                            <a onclick="return confirm('Are you sure you have paid this user?')" class="primary-btn-sm" href="refBonus?markpaid=<?= $row['id'] ?>">Mark as Paid</a>
                                        </td>
                                    </tr> 
                                    <?php }} ?>                                   
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Names</th>
                                        <th>Bank</th>
                                        <th>Acct No</th>
                                        <th>Number of referrals</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets/js/jquery.js"></script>
    <script src="./assets/js/jquery.dataTables.min.js"></script>
    <script src="./assets/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $('#example').DataTable();
    </script>
    <script src="./assets/js/main.js"></script>
</body>
</html>