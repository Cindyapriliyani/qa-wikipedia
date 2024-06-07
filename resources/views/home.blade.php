<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Sistem QA Dokumen Wikipedia</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon"
        href="https://upload.wikimedia.org/wikipedia/commons/8/80/Wikipedia-logo-v2.svg" />

    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet"
        type="text/css" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet"
        type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('template/css/styles.css') }}" rel="stylesheet" />
    <style>
        .warning {
            border-color: red !important;
        }
        
    </style>
</head>

<body>
    <!-- Navigation-->
    <nav class="navbar navbar-light bg-light static-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('qa') }}">
                <img src="{{ asset('template/assets/qa.jpg') }}" class="logo-img" style="width: 5%; height: auto;" />
                Sistem QA Dokumen Wikipedia
            </a>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead" style="background-color: #007bff; padding-bottom: 100px;">
        <div class="container position-relative">
            <div class="row justify-content-center align-items-center">
                <!-- Menggunakan flexbox untuk menyusun input dan tombol -->
                <div class="col-xl-6">
                    <div class="text-center text-white">
                        <!-- Page heading-->
                        <h1 class="mb-5">Topik apa yang ingin kamu cari hari ini?</h1>
                        <form class="form-subscribe" id="contactForm">
                            <!-- Email address input-->
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <input class="form-control form-control-lg" id="topik" type="text"
                                            placeholder="Tulis kata kunci" style="margin-bottom: 20px; width: 105%;"
                                            autofocus />
                                            <div id="topikWarning" class="text-danger"></div>
                                    </div>
                                    
                                    <div class="col-auto" style="margin-left: 10px;">
                                        <button class="btn btn-primary btn-lg" id="searchButton" type="button"
                                            style="background-color: #007bff;">Cari
                                            <span id="searchSpinner" class="loading-spinner" style="display: none;"></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <h5 style="text-align: left;">Deskripsi</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <textarea class="form-control form-control-lg" id="deskripsi" style="height: 300px; width: 100%;" readonly></textarea>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <input class="form-control form-control-lg" id="pertanyaan" type="text"
                                            placeholder="Pertanyaan"  style="margin-bottom: 20px; width: 105%;"
                                        disabled/>
                                
                                    </div>
                                    <div class="col-auto" style="margin-left: 10px;">
                                        <button class="btn btn-primary btn-lg" id="answerButton" type="button"
                                        style="background-color: #007bff;">Jawab
                                        <span id="answerSpinner" class="loading-spinner" style="display: none;"></span>
                                    </button>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col">
                                        <h5 style="text-align: left;">Jawaban</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <textarea class="form-control form-control-lg" id="jawaban" style="height: 150px; width: 100%;" readonly></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Icons Grid-->
    <section class="features-icons bg-light text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                        <div class="features-icons-icon d-flex"><i class="bi-window m-auto text-primary"></i></div>
                        <h3>Apa itu Sistem QA Dokumen Wikipedia?</h3>
                        <p class="lead mb-0">Situs Website yang menyediakan informasi yang tersedia di Wikipedia!</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                        <div class="features-icons-icon d-flex"><i class="bi-layers m-auto text-primary"></i></div>
                        <h3>Informasi/jawaban secara presisi</h3>
                        <p class="lead mb-0">Mendapatkan informasi dengan tepat dan cepat!</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="features-icons-item mx-auto mb-0 mb-lg-3">
                        <div class="features-icons-icon d-flex"><i class="bi-terminal m-auto text-primary"></i></div>
                        <h3>Mudah digunakan</h3>
                        <p class="lead mb-0">Mudah diakses dan dimanfaatkan oleh siapapun!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Image Showcases-->
    <section class="showcase">
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-lg-6 order-lg-2 text-white showcase-img"
                    style="background-image: url('{{ asset('template/assets/img/bg-showcase-1.jpg') }}');"></div>
                <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                    <h2>Sistem QA Dokumen Wikipedia</h2>
                    <p class="lead mb-0">Situs web ini merupakan sumber informasi yang menyediakan konten yang sama
                        dengan yang terdapat di Wikipedia. Dengan akses ke berbagai topik dan artikel yang luas,
                        pengguna dapat dengan mudah menemukan informasi yang mereka butuhkan tanpa harus langsung
                        mengunjungi situs Wikipedia itu sendiri. Hal ini memudahkan pengguna dalam mencari referensi dan
                        pengetahuan tentang berbagai topik, serta meningkatkan keterbukaan dan aksesibilitas informasi
                        bagi semua orang.</p>
                </div>
            </div>
            <div class="row g-0">
                <div class="col-lg-6 text-white showcase-img"
                    style="background-image: url('{{ asset('template/assets/img/bg-showcase-2.jpg') }}');"></div>
                <div class="col-lg-6 my-auto showcase-text">
                    <h2>Informasi/Jawaban Secara Presisi</h2>
                    <p class="lead mb-0">Dapat memperoleh informasi atau jawaban secara presisi. Fitur-fitur pencarian
                        yang canggih dan sumber informasi yang terpercaya memastikan bahwa pengguna dapat menemukan
                        informasi yang akurat dan terverifikasi dengan cepat.</p>
                </div>
            </div>
            <div class="row g-0">
                <div class="col-lg-6 order-lg-2 text-white showcase-img"
                    style="background-image: url('{{ asset('template/assets/img/bg-showcase-3.jpg') }}');"></div>
                <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                    <h2>Mudah Digunakan</h2>
                    <p class="lead mb-0">Website ini dirancang dengan antarmuka yang sederhana dan intuitif, sehingga
                        mudah digunakan oleh siapa pun, baik pengguna baru maupun berpengalaman. Fitur-fitur navigasi
                        yang jelas dan struktur yang terorganisir dengan baik memungkinkan pengguna untuk dengan cepat
                        menemukan informasi yang mereka butuhkan tanpa mengalami kesulitan.</p>
                </div>
            </div>
        </div>
    </section><br><br>

    <!-- Call to Action-->
    <section class="call-to-action text-white text-center" id="signup">
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <h2 class="mb-4">Semoga Membantu!</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer-->
    <footer class="footer bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 h-100 text-center text-lg-start my-auto">
                    <ul class="list-inline mb-2">
                        <li class="list-inline-item"><a href="#!">About</a></li>
                        <li class="list-inline-item">⋅</li>
                        <li class="list-inline-item"><a href="#!">Contact</a></li>
                        <li class="list-inline-item">⋅</li>
                        <li class="list-inline-item"><a href="#!">Terms of Use</a></li>
                        <li class="list-inline-item">⋅</li>
                        <li class="list-inline-item"><a href="#!">Privacy Policy</a></li>
                    </ul>
                    <p class="text-muted small mb-4 mb-lg-0">&copy; Sistem QA Dokumen Wikipedia 2024. All Rights
                        Reserved.</p>
                </div>
                <div class="col-lg-6 h-100 text-center text-lg-end my-auto">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item me-4">
                            <a href="#!"><i class="bi-facebook fs-3"></i></a>
                        </li>
                        <li class="list-inline-item me-4">
                            <a href="#!"><i class="bi-twitter fs-3"></i></a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#!"><i class="bi-instagram fs-3"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="{{ asset('template/js/scripts.js') }}"></script>
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>


    <script type="text/javascript">
        document.getElementById("searchButton").addEventListener("click", searchTopic);
        document.getElementById("topik").addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                event.preventDefault();
                searchTopic();
                document.getElementById('topik').blur();
            }
        });
    
        document.getElementById("answerButton").addEventListener("click", getAnswer);
        document.getElementById("pertanyaan").addEventListener("keydown", (event) => {
            if (event.key === "Enter") {
                event.preventDefault();
                getAnswer();
                document.getElementById('pertanyaan').blur();
            }
        });
    
        function searchTopic() {
            const topic = document.getElementById('topik').value;
            const descriptionTextarea = document.getElementById('deskripsi');
            const questionInput = document.getElementById('pertanyaan');
            const answerButton = document.getElementById('answerButton');
            const searchSpinner = document.getElementById('searchSpinner');
    
            if (!topic) {
                document.getElementById('topik').placeholder = 'Mohon masukkan topik!';
                document.getElementById('topik').classList.add('warning');
                return;
            } else {
                document.getElementById('topik').placeholder = 'Tulis kata kunci';
                document.getElementById('topik').classList.remove('warning');
            }
    
            descriptionTextarea.value = 'Loading...';
            searchSpinner.style.display = 'inline-block';
    
            const url = `https://id.wikipedia.org/api/rest_v1/page/html/${topic}`;
    
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(htmlContent => {
                    const description = extractTextFromHtml(htmlContent);
                    descriptionTextarea.value = description;
    
                    questionInput.disabled = false;
                    answerButton.disabled = false;
                })
                .catch(error => {
                    descriptionTextarea.value = 'Error fetching data: ' + error.message;
                })
                .finally(() => {
                    searchSpinner.style.display = 'none';
                });
    
            // Clear the question and answer fields when topic changes
            document.getElementById('pertanyaan').value = '';
            document.getElementById('jawaban').value = '';
            questionInput.disabled = true;
            answerButton.disabled = true;
        }
    
        function getAnswer() {
            const context = document.getElementById('deskripsi').value;
            const question = document.getElementById('pertanyaan').value;
            const answerTextarea = document.getElementById('jawaban');
            const answerSpinner = document.getElementById('answerSpinner');
            const pertanyaanWarning = document.getElementById('pertanyaanWarning');
    
            if (!question) {
                document.getElementById('pertanyaan').placeholder = 'Mohon masukkan pertanyaan!';
                document.getElementById('pertanyaan').classList.add('warning');
                return;
            } else {
                document.getElementById('pertanyaan').placeholder = 'Pertanyaan';
                document.getElementById('pertanyaan').classList.remove('warning');
            }
            
            answerTextarea.value = 'Loading...';
            answerSpinner.style.display = 'inline-block';
    
            fetch('http://127.0.0.1:5000/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    context: context,
                    question: question
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(result => {
                answerTextarea.value = result.answer || 'No answer found';
            })
            .catch(error => {
                answerTextarea.value = 'Error fetching data: ' + error.message;
            })
            .finally(() => {
                answerSpinner.style.display = 'none';
            });
        }
    
        function extractTextFromHtml(html) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const paragraphs = doc.querySelectorAll('p');
            let description = '';
            paragraphs.forEach(paragraph => {
                description += paragraph.textContent.trim() + '\n';
            });
            return description;
        }
    </script>
</body>

</html>
