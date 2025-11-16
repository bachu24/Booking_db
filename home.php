<?php
// index.php - Home page (front-end only)
// Place assets in /assets folder: home-bg.jpg, card1.jpg, card2.jpg, card3.jpg
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Evenity — Explore The World</title>

  <!-- Fonts (optional, remove if offline) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="css/home.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <div class="brand">
        <img src="assets/logo-tickets.png" alt="logo" class="logo" onerror="this.style.display='none'">
        <div class="brand-txt">
          <div class="brand-name">Evenity</div>
          <div class="brand-sub">Booking Service</div>
        </div>
      </div>

      <nav class="main-nav">
        <a class="nav-link active" href="#">Home</a>
        <a class="nav-link" href="#">Services</a>
        <a class="nav-link" href="#">Contact Us</a>
        <a class="nav-link" href="#">Blog</a>
      </nav>

      <div class="header-right">
        <div class="search">
          <input type="text" placeholder="Search..." aria-label="search">
          <button class="search-btn" aria-hidden="true">🔍</button>
        </div>
        <a class="btn login" href="login.php">Log In</a>
      </div>
    </div>
  </header>

  <main class="hero-wrap">
    <div class="bg-overlay"></div>
    <div class="container hero">
      <div class="hero-left">
        <div class="vertical-bar"></div>
        <h1 class="hero-title"><span class="big">EXPLORE</span><br><span class="accent">THE WORLD</span></h1>
        <p class="hero-desc">
          Step into a world of unforgettable experiences. Our platform connects people with the events they love,
          offering a seamless and reliable ticket booking journey. From searching to securing your seat, Evenity ensures every moment starts beautifully.
        </p>
        <div class="hero-cta">
          <a class="btn-primary" href="#">READ MORE</a>
        </div>
      </div>

      <div class="hero-right">
        <div class="carousel" id="cardCarousel">
          <button class="carousel-nav left" id="prevBtn" aria-label="previous">❮</button>

          <div class="cards">
            <div class="card card-1" data-index="0">
              <div class="card-image"><img src="assets/event2.webp" alt="NCT Concert"></div>
              <div class="card-body">
                <h3 class="card-title">NCT CONCERT</h3>
                <p class="card-text">Get ready and join the fun with amazing performances.</p>
                <a class="btn-ghost" href="/customer/event-details.php">Booking ➜</a>
              </div>
            </div>

            <div class="card card-2 active" data-index="1">
              <div class="card-image"><img src="assets/event1.webp" alt="RIIZE Concert"></div>
              <div class="card-body">
                <h3 class="card-title">RIIZE CONCERT</h3>
                <p class="card-text">Are you ready to unleash your full energy and have an unforgettable time?</p>
                <a class="btn-ghost" href="/customer/event-details.php">Booking ➜</a>
              </div>
            </div>

            <div class="card card-3" data-index="2">
              <div class="card-image"><img src="assets/tws.webp" alt="TWS Concert"></div>
              <div class="card-body">
                <h3 class="card-title">TWS CONCERT</h3>
                <p class="card-text">Are you ready for a night full of amazing music?</p>
                <a class="btn-ghost" href="/customer/event-details.php">Booking ➜</a>
              </div>
            </div>
          </div>

          <button class="carousel-nav right" id="nextBtn" aria-label="next">❯</button>
        </div>
      </div>

    </div>
  </main>

  <script>
    // Minimal carousel logic: shift "active" class among cards
    (function(){
      const carousel = document.getElementById('cardCarousel');
      const cards = carousel.querySelectorAll('.card');
      let idx = 1; // start with center active (card-2)

      function show(i){
        cards.forEach((c,ci)=>{
          c.classList.remove('active','left','right','back-left','back-right');
          if(ci === i) c.classList.add('active');
          else if(ci === (i-1+cards.length)%cards.length) c.classList.add('left');
          else if(ci === (i+1)%cards.length) c.classList.add('right');
          else if(ci === (i-2+cards.length)%cards.length) c.classList.add('back-left');
          else c.classList.add('back-right');
        });
      }

      document.getElementById('prevBtn').addEventListener('click', ()=>{
        idx = (idx-1+cards.length) % cards.length;
        show(idx);
      });
      document.getElementById('nextBtn').addEventListener('click', ()=>{
        idx = (idx+1) % cards.length;
        show(idx);
      });

      // auto rotate (optional)
      let auto = setInterval(()=>{ idx = (idx+1)%cards.length; show(idx); }, 6000);
      // pause on hover
      carousel.addEventListener('mouseenter', ()=>clearInterval(auto));
      carousel.addEventListener('mouseleave', ()=>{ auto = setInterval(()=>{ idx = (idx+1)%cards.length; show(idx); }, 6000); });

      // initial layout
      show(idx);
    })();
  </script>
</body>
</html>
