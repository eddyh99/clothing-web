<?php require_once('main.php'); // outputs <!doctype html><html ... > ?>
<head>
    <?php
        require_once('title-meta.php');    // meta tags and title
        require_once('head-css.php');      // CSS and layout.js
    ?>
</head>
<body>   
    <div id="layout-wrapper">
        <?php
            require_once('topbar.php');
            require_once('sidebar.php');
        ?>
        <!-- Success Alert -->
        <?php if (!empty($_SESSION["success"])):?>
            <div class="alert alert-success alert-dismissible shadow fade show alert-floating-top-right" role="alert">
                <?=$_SESSION["success"]?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif;?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                <?php
                    require_once('page-title.php');
                    if (isset($content)) {
                        $errors = $_SESSION["failed"] ?? [];
                        
                        // Normalisasi errors menjadi array flat
                        $flatErrors = [];
                        
                        if (!is_array($errors)) {
                            $errors = [$errors];
                        }
                        
                        foreach ($errors as $error) {
                            if (is_array($error)) {
                                // Jika error berupa array assosiatif (validation errors)
                                foreach ($error as $key => $message) {
                                    if (is_array($message)) {
                                        // Jika terdapat nested array (multiple errors per field)
                                        $flatErrors = array_merge($flatErrors, $message);
                                    } else {
                                        $flatErrors[] = $message;
                                    }
                                }
                            } else {
                                // Jika error berupa string
                                $flatErrors[] = $error;
                            }
                        }
                        ?>

                        <?php if (!empty($flatErrors)): ?>
                            <div class="alert alert-danger alert-dismissible shadow fade show" role="alert">
                                <ul>
                                    <?php foreach ($flatErrors as $error): ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach ?>
                                </ul>
                            </div>
                        <?php endif;

                        echo view($content);
                    }

                    require_once('footer.php');
                    ?>       
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
        </div>
        <!-- end main content-->
    </div>
     <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>    
    <?php
        require_once('vendor-scripts.php'); // JS libraries
    ?>
</body>
</html>
