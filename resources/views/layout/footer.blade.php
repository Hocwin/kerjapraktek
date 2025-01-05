<style>
    /* Footer styling */

    body {
        height: 100%;
        margin: 0;
        display: flex;
        flex-direction: column;
    }

    /* Content area to fill available space */
    body>*:not(footer) {
        flex: 1 0 auto;
        /* Stretch content area */
    }

    footer {
        flex-shrink: 0;
        margin-top: 20px;
        background-color: #f8f9fa;
        text-align: center;
        width: 100%;
        padding: 1rem 0;
    }

    /* Responsive design tweaks */
    @media (max-width: 768px) {
        footer {
            padding: 0.75rem 0;
        }

        footer .list-inline-item {
            margin: 0 3px;
        }

        footer p {
            font-size: 0.9rem;
        }
    }
</style>

<footer class="bg-light text-dark pt-3 pb-2">
    <div class="container text-center text-md-left">
        <div class="row text-center text-md-left">
            <div class="col-12 d-flex justify-content-center mb-2 mb-md-0">
                <p class="mb-0">
                    Copyright 2024 All Rights Reserved By :
                    <a href="#" style="text-decoration:none;">
                        <strong class="text-info">Amelia Putratama Mandiri</strong>
                    </a>
                </p>
            </div>
            <div class="col-12 d-flex justify-content-center">
                <ul class="list-unstyled list-inline mb-0">
                    <li class="list-inline-item">
                        <a href="#" class="text-dark" style="font-size: 1.25rem; padding: 0 10px;"><i class="fab fa-facebook"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-dark" style="font-size: 1.25rem; padding: 0 10px;"><i class="fab fa-twitter"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-dark" style="font-size: 1.25rem; padding: 0 10px;"><i class="fab fa-instagram"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>