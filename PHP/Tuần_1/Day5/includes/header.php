<nav class="navbar navbar-expand-lg navbar-light bg-light">
     <div class="container-fluid">
          <a class="navbar-brand" href="index.php">Activity Logger</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
               <ul class="navbar-nav">
                    <li class="nav-item">
                         <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" href="view_log.php">View Logs</a>
                    </li>
               </ul>
               <ul class="navbar-nav ms-auto">
                    <?php
                    if (isset($_SESSION['user'])) {
                         echo '<li class="nav-item"><span class="nav-link">Welcome, ' . htmlspecialchars($_SESSION['user']) . '</span></li>';
                         echo '<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>';
                    } else {
                         echo '<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>';
                    }
                    ?>
               </ul>
          </div>
     </div>
</nav>