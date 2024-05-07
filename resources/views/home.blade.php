<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Sistem QA Dokumen WikiPedia</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="https://upload.qa.org/qa/en/8/80/qa-logo-v2.svg" />

        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" type="text/css" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ asset('template/css/styles.css') }}" rel="stylesheet" />
    </head>
    
    <body>
        <!-- Navigation-->
        <nav class="navbar navbar-light bg-light static-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('qa') }}">
                    <img src="{{  asset('template/assets/qa.jpg')}}" class="logo-img" style="width: 5%; height: auto;"/>
                    Sistem QA Dokumen Wikipedia
                </a>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead" style="background-color: #007bff; padding-bottom: 100px;">
            <div class="container position-relative">
                <div class="row justify-content-center align-items-center"> <!-- Menggunakan flexbox untuk menyusun input dan tombol -->
                    <div class="col-xl-6">
                        <div class="text-center text-white">
                            <!-- Page heading-->
                            <h1 class="mb-5">Topik apa yang ingin kamu cari hari ini?</h1>
                            <form class="form-subscribe" id="contactForm" data-sb-form-api-token="API_TOKEN">
                                <!-- Email address input-->
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <input class="form-control form-control-lg" id="topik" type="text" placeholder="Tulis kata kunci" style="margin-bottom: 20px; width: 105%;" autofocus />
                                        </div>
                                        <div class="col-auto" style="margin-left: 10px;">
                                            <button class="btn btn-primary btn-lg" id="searchButton" type="button" style="background-color: #007bff;">Cari</button>
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
                                    
                                <div id="deskripsi"></div>
                                    <script>

                                let descriptionInterval; // Menyimpan interval untuk deskripsi

                                 // Function to fetch Wikipedia description based on the topic
                                 async function fetchDescription(topic) {
                                    try {
                                        const response = await fetch(`https://id.wikipedia.org/api/rest_v1/page/html/${topic}`);
                                        const html = await response.text();
                                        const description = extractTextFromHtml(html);
                                        if (description.trim() === '') {
                                            return "Deskripsi tidak tersedia untuk topik ini.";
                                        }
                                        return description;
                                    } catch (error) {
                                        console.error('Error fetching description:', error);
                                        return "Terjadi kesalahan saat mengambil deskripsi.";
                                    }
                                }

                                // Function to start fetching and updating description continuously
                                    function startFetchingDescription(topic) {
                                        // Fetch description initially
                                        fetchAndUpdateDescription(topic);

                                        // Set interval to fetch and update description continuously
                                        descriptionInterval = setInterval(async () => {
                                            fetchAndUpdateDescription(topic);
                                        }, 60000); // Fetch and update every 1 minute (adjust as needed)
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

            
                                 // Function to handle search and typing effect
                                        async function handleSearch() {
                                            const topic = document.getElementById('topik').value;
                                            const deskripsiElement = document.getElementById('deskripsi');
                                            deskripsiElement.value = ''; // Clear previous content

                                         if (topic.trim() === '') {
                                            alert('Mohon masukkan topik terlebih dahulu!');
                                            return; // Stop further execution
                                        }
                                        const description = await fetchDescription(topic);
                                        await typeWithEffect(description, deskripsiElement, 0); // Typing speed (adjust as needed)
                                        
                                // Enable the question input field
                                        document.getElementById('pertanyaan').removeAttribute('disabled');
                                        // Set focus to the question input field
                                        document.getElementById('pertanyaan').focus();
                                    }


                                // Resize the description button based on the content
                                        resizeDescriptionButton();

                                 // Function to handle answering questions
                                    async function handleAnswering() {
                                        const question = document.getElementById('pertanyaan').value.toLowerCase();
                                        const description = document.getElementById('deskripsi').value.toLowerCase();
                                        const answer = findAnswerInDescription(question, description);
                                        const jawabanElement = document.getElementById('jawaban');
                                        jawabanElement.value = ''; // Clear previous content
                                        await typeWithEffect(answer || "Maaf, saya tidak bisa menemukan jawaban atas pertanyaan Anda dalam deskripsi tersebut.", jawabanElement, 25); // Typing speed (adjust as needed)
                                    }
                                                                    
                                 // Function to resize description button based on content
                                        function resizeDescriptionButton() {
                                            const deskripsiElement = document.getElementById('deskripsi');
                                            const deskripsiLength = deskripsiElement.value.length;
                                            const buttonElement = document.getElementById('searchButton');
                                            const defaultWidth = 100; // Default width of the button
                                
                                // Adjust button width based on description length
                                        buttonElement.style.width = `${defaultWidth}px`;
                                   }
                                
                                // Function to simulate typing effect with scrolling
                                async function typeWithScrolling(text, element, speed) {
                                    for (let i = 0; i <= text.length; i++) {
                                        await delay(speed);
                                        element.value = text.substring(0, i);

                                // Scroll to the bottom
                                        element.scrollTop = element.scrollHeight;
                                    }
                                }

                                // Function to simulate delay
                                function delay(ms) {
                                    return new Promise(resolve => setTimeout(resolve, ms));
                                }

                                // Example usage
                                const description = "";
                                const deskripsiElement = document.getElementById('deskripsi');
                                typeWithScrolling(description, deskripsiElement, 50); // Adjust typing speed as needed
                                
                                // Function to answer questions
                                async function answerQuestion() {
                                    const question = document.getElementById('pertanyaan').value.toLowerCase();
                                    const description = document.getElementById('deskripsi').value.toLowerCase();
                                    const answer = findMostRelevantContext(question, description);
                                    const jawabanElement = document.getElementById('jawaban');
                                    jawabanElement.value = ''; // Clear previous content
                                    await typeWithEffect(answer, jawabanElement, 0); // Typing speed (adjust as needed)
                                }

                            // Function to find the most relevant context in the description to the question
                            function findMostRelevantContext(question, description) {
                                const tokens1 = question.split(' ');
                                const tokens2 = description.split(' ');
    
                                let maxMatch = 0;
                                let bestStartIndex = 0;
                                let bestEndIndex = 0;
    
                            // Iterate through each token in the description
                            for (let i = 0; i < tokens2.length; i++) {
                                let matchCount = 0;
                                let j = 0;

                            // Iterate through each token in the question
                            while (i + j < tokens2.length && j < tokens1.length) {
                                // If tokens match, increment match count
                                if (tokens1[j] === tokens2[i + j]) {
                                    matchCount++;
                                }
                                j++;
                            }
                                                
                        // Update max match and indices if current match count is higher
                            if (matchCount > maxMatch) {
                                maxMatch = matchCount;
                                bestStartIndex = i;
                                bestEndIndex = i + j - 1;
                            }
                        }
    
                        // Construct the context from the best matching tokens
                            const contextTokens = tokens2.slice(bestStartIndex, bestEndIndex + 1);
                            const context = contextTokens.join(' ');
                            
                            return context;
                        }

                        // Function to find answer in the description
                            function findAnswerInDescription(question, description) {

                        // Split the description into sentences
                            const sentences = description.split('.');

                        // Search for the question in each sentence
                            for (let i = 0; i < sentences.length; i++) {
                                const sentence = sentences[i].trim();

                        // If the sentence contains the question, return the sentence as the answer
                            if (sentence.includes(question)) {
                                return sentence;
                                                }
                            }
                                return ''; // Return empty string if answer is not found
                        }
                                
                        // Event listener for search button
                            document.getElementById('searchButton').addEventListener('click', handleSearch);
                                
                        // Event listener for pressing Enter key on the 'topik' input
                            document.getElementById('topik').addEventListener('keypress', function(event) {
                                if (event.key === 'Enter') {
                                    event.preventDefault();
                                    handleSearch();

                        // Remove focus from the 'topik' input
                                document.getElementById('topik').blur();
                            }
                     });

                        // Event listener for pressing Enter key on the 'pertanyaan' input
                            document.getElementById('pertanyaan').addEventListener('keypress', function(event) {
                                if (event.key === 'Enter') {
                                    event.preventDefault();
                                    handleSearch();

                        // Remove focus from the 'pertanyaan' input
                                    this.blur(); // 'this' refers to the 'pertanyaan' input element
                                }
                     });

                         // Event listener for answer button
                             document.getElementById('answerButton').addEventListener('click', answerQuestion);

        </script>

                                    <div class="row" style="margin-top: 20px;">
                                        <div class="col">
                                            <input class="form-control form-control-lg" id="pertanyaan" type="text" placeholder="Pertanyaan" style="margin-bottom: 20px; width: 105%;" autofocus disabled/>
                                        </div>
                                        <div class="col-auto" style="margin-left: 10px;">
                                            <button class="btn btn-primary btn-lg" id="answerButton" type="button" style="background-color: #007bff;">Jawab</button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <h5 style="text-align: left;">Jawaban</h5>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <textarea class="form-control form-control-lg" id="jawaban" style="margin-bottom: 20px; width: 100%;" readonly></textarea>
                                        </div>
                                    </div>
                                  
                                <div class="d-none" id="submitSuccessMessage">
                                    <div class="text-center mb-3">
                                        <div class="fw-bolder">Form submission successful!</div>
                                        <p>To activate this form, sign up at</p>
                                        <a class="text-white" href="https://startbootstrap.com/solution/contact-forms">https://startbootstrap.com/solution/contact-forms</a>
                                    </div>
                                </div>
                                <div class="d-none" id="submitErrorMessage"><div class="text-center text-danger mb-3">Error sending message!</div></div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        let topicEntered = false; // Menyimpan status apakah topik sudah dimasukkan atau belum
        // Function to simulate typing effect
        async function typeWithEffect(text, element, speed) {
            for (let i = 0; i <= text.length; i++) {
                await delay(speed);
                element.value = text.substring(0, i);
            }
        }

    // Function to answer questions
    async function answerQuestion() {
        const question = document.getElementById('pertanyaan').value.toLowerCase();
        const description = document.getElementById('deskripsi').value.toLowerCase();
        const answer = findAnswerInDescription(question, description);
        const jawabanElement = document.getElementById('jawaban');
        jawabanElement.value = ''; // Clear previous content
        await typeWithEffect(answer || "Maaf, saya tidak bisa menemukan jawaban atas pertanyaan Anda dalam deskripsi tersebut.", jawabanElement, 25); // Typing speed (adjust as needed)
    }

    // Function to find answer in the description
        function findAnswerInDescription(question, description) {

    // Split the description into sentences
        const sentences = description.split('.');

    // Search for the question in each sentence
        for (let i = 0; i < sentences.length; i++) {
            const sentence = sentences[i].trim();

    // If the sentence contains the question, return the sentence as the answer
        if (sentence.includes(question)) {
                return sentence;
            }
        }
        return ''; // Return empty string if answer is not found
    }

    // Function to simulate delay
    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
    

    // Event listener for search button
    document.getElementById('searchButton').addEventListener('click', async function () {
        const topic = document.getElementById('topik').value;
        const deskripsiElement = document.getElementById('deskripsi');
        deskripsiElement.value = ''; // Clear previous content

        const description = await fetchDescription(topic);
        await typeWithEffect(description, deskripsiElement, 25); // Typing speed (adjust as needed)

    // Resize the description button based on the content
        resizeDescriptionButton();
    });
    

    // Event listener for pressing Enter key on the 'topik' input
    document.getElementById('topik').addEventListener('keypress', async function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();

    // Simulate handling the search action
        console.log('Search action triggered');
        
    // Clear the text from the 'pertanyaan' input
        document.getElementById('pertanyaan').value = '';

    // Clear the text from the 'jawaban' input
        document.getElementById('jawaban').value = '';

    // Remove focus from the 'topik' input
        this.blur(); // 'this' refers to the 'topik' input element
     }
});

    // Event listener for pressing Enter key on the 'pertanyaan' input
    document.getElementById('pertanyaan').addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            answerQuestion();
        }
    });

    // Event listener for answer button
    document.getElementById('answerButton').addEventListener('click', function () {
        answerQuestion();
    });

</script>
        <!-- Icons Grid-->
        <section class="features-icons bg-light text-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                            <div class="features-icons-icon d-flex"><i class="bi-window m-auto text-primary"></i></div>
                            <h3>Apa itu Sistem QA Dokumen Wikipedia?</h3>
                            <p class="lead mb-0">Situs Website yang menyedikan informasi yang tersedia di WikiPedia!</p>
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
                            <h3>Mudah digunakan</h3> <br>
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
                    <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('{{ asset('template/assets/img/bg-showcase-1.jpg') }}');"></div>
                    <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                        <h2>Sistem QA Dokumen Wikipedia</h2>
                        <p class="lead mb-0">Situs web ini merupakan sumber informasi yang menyediakan konten yang sama dengan yang terdapat di Wikipedia. Dengan akses ke berbagai topik dan artikel yang luas, pengguna dapat dengan mudah menemukan informasi yang mereka butuhkan tanpa harus langsung mengunjungi situs Wikipedia itu sendiri. Hal ini memudahkan pengguna dalam mencari referensi dan pengetahuan tentang berbagai topik, serta meningkatkan keterbukaan dan aksesibilitas informasi bagi semua orang.</p>
                    </div>
                </div>
                <div class="row g-0">
                    <div class="col-lg-6 text-white showcase-img" style="background-image: url('{{  asset('template/assets/img/bg-showcase-2.jpg') }}');"></div>
                    <div class="col-lg-6 my-auto showcase-text">
                        <h2>Informasi/Jawaban Secara Presisi</h2>
                        <p class="lead mb-0">Dapat memperoleh informasi atau jawaban secara presisi. Fitur-fitur pencarian yang canggih dan sumber informasi yang terpercaya memastikan bahwa pengguna dapat menemukan informasi yang akurat dan terverifikasi dengan cepat. </p>
                    </div>
                </div>
                <div class="row g-0">
                    <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('{{  asset('template/assets/img/bg-showcase-3.jpg') }}');"></div>
                    <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                        <h2>Mudah Digunakan</h2>
                        <p class="lead mb-0">Website ini dirancang dengan antarmuka yang sederhana dan intuitif, sehingga mudah digunakan oleh siapa pun, baik pengguna baru maupun berpengalaman. Fitur-fitur navigasi yang jelas dan struktur yang terorganisir dengan baik memungkinkan pengguna untuk dengan cepat menemukan informasi yang mereka butuhkan tanpa mengalami kesulitan.</p>
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
                        <p class="text-muted small mb-4 mb-lg-0">&copy; Sistem QA Dokumen Wikipedia 2024. All Rights Reserved.</p>
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
        <script src="{{  asset('template/js/scripts.js') }}"></script>
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>
</html>