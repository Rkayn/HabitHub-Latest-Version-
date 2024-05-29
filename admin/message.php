<?php 
        if(isset($_SESSION['status']))
            {
                ?>
                <div class="alert alert-warning alert-dismissable fade show" role="alert">
                    <strong>Hey!</strong> <?php echo $_SESSION['status']; ?>
                    <button type = "button" class="close" data-dismiss="alert" arial-label= "Close">
                        <span aria-hidden ="true">&times;</span>                   
                    </button>
            </div>
                <?php
                unset($_SESSION['status']);

            }

?>