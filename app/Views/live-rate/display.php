<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <?= view('layout/head-css') ?>
    <style nonce="<?= $nonce ?>">
        /* TV Display Optimization */
        body {
            font-family: 'Arial', sans-serif;
            overflow: hidden; /* Prevent scrollbars on TV */
            background: linear-gradient(135deg, #1a365d 0%, #2a4a7e 100%);
            margin: 0;
            padding: 0;
        }

        .tv-container {
            height: 100vh;
            width: 100vw;
            padding: 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .currency-flag {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .rate-card {
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            height: 70px;
            border-radius: 8px;
        }

        .rate-card:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .table-header {
            background: linear-gradient(135deg, #4a6fc1 0%, #3b5998 100%);
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .currency-code-tv {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a365d;
            letter-spacing: 0.5px;
        }

        .rate-value-tv {
            font-size: 1.6rem;
            font-weight: 800;
            color: #2b6cb0;
        }

        .header-text-tv {
            font-size: 1.4rem;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transition: all 0.4s ease;
            border: 2px solid rgba(255, 255, 255, 0.6);
            cursor: pointer;
        }

        .dot.active {
            background: #ffffff;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
            transform: scale(1.2);
        }

        .min-height-tv {
            min-height: calc(100vh - 180px);
            display: flex;
            flex-direction: column;
        }

        .carousel-item {
            transition: transform 0.8s ease-in-out;
        }

        /* Grid layout for 21 items (7 rows x 3 columns) */
        .rates-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 70px;
            gap: 12px;
            height: calc(7 * 70px + 6 * 12px); /* 7 rows with gaps */
        }

        /* Responsive adjustments */
        @media (min-width: 1920px) {
            .currency-code-tv { font-size: 1.6rem; }
            .rate-value-tv { font-size: 1.8rem; }
            .header-text-tv { font-size: 1.6rem; }
            
            .rates-grid {
                grid-auto-rows: 80px;
                height: calc(7 * 80px + 6 * 12px);
            }
            
            .rate-card {
                height: 80px;
            }
        }

        @media (max-width: 1366px) {
            .currency-code-tv { font-size: 1.2rem; }
            .rate-value-tv { font-size: 1.4rem; }
            .header-text-tv { font-size: 1.2rem; }
            
            .rates-grid {
                grid-auto-rows: 65px;
                height: calc(7 * 65px + 6 * 10px);
                gap: 10px;
            }
            
            .rate-card {
                height: 65px;
            }
            
            .currency-flag {
                width: 35px;
                height: 35px;
            }
        }
        
        @media (max-height: 800px) {
            .tv-container {
                padding: 15px;
            }
            
            .rates-grid {
                grid-auto-rows: 60px;
                height: calc(7 * 60px + 6 * 8px);
                gap: 8px;
            }
            
            .rate-card {
                height: 60px;
            }
            
            .currency-flag {
                width: 30px;
                height: 30px;
            }
            
            .currency-code-tv { font-size: 1.1rem; }
            .rate-value-tv { font-size: 1.3rem; }
        }
    </style>
</head>

<body>
    <div class="tv-container">
        <!-- Table Headers -->
        <div class="row">
            <div class="col-12">
                <div class="table-header text-white p-3 rounded">
                    <div class="row">
                        <div class="col-4 text-center">
                            <span class="header-text-tv">Currency</span>
                        </div>
                        <div class="col-4 text-center">
                            <span class="header-text-tv">Currency</span>
                        </div>
                        <div class="col-4 text-center">
                            <span class="header-text-tv">Currency</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel Container -->
        <div class="min-height-tv">
            <!-- Carousel -->
            <div id="rateSlider" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="15000">
                <div class="carousel-inner h-100">
                    <?php
                    // Split rates into chunks of exactly 21 items
                    $chunks = array_chunk($rates, 21);
                    foreach ($chunks as $index => $rateChunk):
                    ?>
                        <div class="carousel-item h-100 <?= $index === 0 ? 'active' : '' ?>">
                            <div class="rates-grid">
                                <?php foreach ($rateChunk as $rate): ?>
                                    <div class="rate-card shadow border-0">
                                        <div class="card-body p-2 d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <?php if ($rate['flag']): ?>
                                                    <img src="<?= $rate['flag'] ?>" class="currency-flag me-2" alt="<?= $rate['currency'] ?>">
                                                <?php endif; ?>
                                                <div class="currency-code-tv"><?= $rate['currency_id'] ?></div>
                                            </div>
                                            <div class="rate-value-tv"><?= $rate['buy_rate'] ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Navigation Dots -->
            <?php if (count($chunks) > 1): ?>
            <div class="d-flex justify-content-center align-items-center gap-2 py-3 mt-2">
                <?php for ($i = 0; $i < count($chunks); $i++): ?>
                    <div class="dot <?= $i === 0 ? 'active' : '' ?>" 
                         data-bs-target="#rateSlider" 
                         data-bs-slide-to="<?= $i ?>"
                         role="button"
                         aria-label="Slide <?= $i + 1 ?>"></div>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?= view('layout/vendor-scripts') ?>
    <script nonce="<?= $nonce ?>">
        $(document).ready(function() {
            // TV-optimized carousel with longer interval
            const carousel = new bootstrap.Carousel($('#rateSlider')[0], {
                interval: 15000, // 15 seconds for TV viewing
                ride: 'carousel',
                wrap: true,
                touch: false // Disable touch for TV
            });

            // Smooth dot transitions
            $('#rateSlider').on('slide.bs.carousel', function(e) {
                $('.dot').removeClass('active').css('transform', 'scale(1)');
                const activeDot = $(`.dot[data-bs-slide-to="${e.to}"]`);
                activeDot.addClass('active').css('transform', 'scale(1.2)');
            });

            // Click on dots to change slide
            $('.dot').on('click', function() {
                const slideIndex = $(this).data('bs-slide-to');
                carousel.to(slideIndex);
            });

            // TV-specific keyboard controls
            $(document).on('keydown', function(e) {
                switch(e.key) {
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        e.preventDefault();
                        carousel.prev();
                        break;
                    case 'ArrowRight':
                    case 'ArrowDown':
                        e.preventDefault();
                        carousel.next();
                        break;
                }
            });

            // Prevent screen saver/sleep mode
            let wakeLock = null;
            if ('wakeLock' in navigator) {
                navigator.wakeLock.request('screen').then(lock => {
                    wakeLock = lock;
                    console.log('Screen wake lock activated for TV display');
                }).catch(err => {
                    console.log('Wake lock failed:', err);
                });
            }

            // TV display optimization
            $('body').css({
                'user-select': 'none',
                '-webkit-user-select': 'none',
                '-moz-user-select': 'none',
                '-ms-user-select': 'none'
            });
        });
    </script>
</body>

</html>