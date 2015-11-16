<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="index.php" class="navbar-brand">The Talent Showoff</a>
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
                <li><a href="index.php" id="themes">Home </a></li>
                <?php
                    if ($user_ok == TRUE){    
                        $userId = preg_replace('#[^0-9]#i', '', $log_id);
                        $userName = preg_replace('#[^a-z0-9]#i', '', $log_username);
                        $userlavel = getUserField('userlevel',$userId);
                        
                        // a default user
                        // c for performer
                        // b for super user
                        
                        if ($userlavel == 'c'){
                            echo'<li class="dropdown">';
                            echo'<a class="dropdown-toggle" data-toggle="dropdown" href="dashboard.php" id="download">Dashboard <span class="caret"></span></a>';
                            echo'<ul class="dropdown-menu" aria-labelledby="download"> ';
                            echo '<li><a href="add-video.php">Add Video</a></li>';
                            echo '<li><a href="Dashboard.php">Contest Status</a></li>';
                            echo'</ul>';
                            echo'</li>';
                        } elseif ($userlavel == 'b'){   
                            echo'<li class="dropdown">';
                            echo'<a class="dropdown-toggle" data-toggle="dropdown" href="dashboard.php" id="download">Dashboard <span class="caret"></span></a>';
                            echo'<ul class="dropdown-menu" aria-labelledby="download"> ';
                            echo '<li><a href="add-video.php">Add Video</a></li>';
                            echo '<li><a href="Dashboard.php">Contest Status</a></li>';
                            echo '<li class="divider"></li>';
                            echo '<li><a href="add-contest.php">Add Contest</a></li>';
                            echo '<li><a href="publish-video.php">Publish Video</a></li>';
                            echo'</ul>';
                            echo'</li>';
                        }
                                      
                    }
                ?>                                               
            </ul>            
            <p class="pull-right"><?php echo $loginLink; ?></p>
        </div>
    </div>
</div> 