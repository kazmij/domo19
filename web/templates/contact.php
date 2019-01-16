<?php include('partials/head.php'); ?>
<?php
$desktop_header_class = "";
$is_home = false;
?>
<?php include('partials/desktop-header.php'); ?>
<?php include('partials/mobile-header.php'); ?>

<section class="container">
  <header class="row with-square with-square-100">
    <h1 class="col-xs-12 default-headline default-headline-huge">
      Kontakt
      <small>Prosimy o skorzystanie z formularza kontaktowego lub kontakt telefoniczny.</small>
    </h1>
  </header>
  <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
      <form id="contact-form" class="content-box contact-form">
        <div class="form-item  form-item-with-label">
          <label>Imię i nazwsko:</label>
          <input type="text" placeholder="Jan Kowalski" name="" />
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-item">
              <input type="email" placeholder="Adres e-mail" name="" />
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-6">
            <div class="form-item">
              <input type="tel" placeholder="Telefon" name="" />
            </div>
          </div>
        </div>
        <div class="row">
        <div class="col-xs-12">
          <div class="form-item form-item-file">
            <label>Załącznik: <i class="info-badge2" data-tooltip="Lorem ipsum dolor sit amet"></i></label>
            <div class="file-holder">
              <input type="file" name="" />
            </div>
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-xs-12">
          <div class="form-item">
            <textarea name="" placeholder="Treść"></textarea>
          </div>
        </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-md-6">
            [CAPTCHA PLACEHOLDER]
          </div>
          <div class="col-xs-12 col-md-6">
            <button type="submit" class="button button-big button-filled button-filled-yellow">
              Wyślij
            </button>
          </div>
        </div>
      </form>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
      <section class="sidebar-box">
        <header>
          <h2>
            Dane kontaktowe
          </h2>
        </header>
        <div class="sidebar-box-content">
          <div class="contact-about">
            <h3>DREAM HOUSE HUBERT WAŚ</h3>
            <p>UL. RADZYMIŃSKA 230<br />
              03-674 WARSZAWA<br />
              NIP: 5242572990<br />
              REGON: 142834212</p>
          </div>
          <div class="contact-persons">
            <div class="contact-person">
              <img src="/img/avatars/avatar-jan.png" alt="" />
              <div class="contact-person-info">
                <h3>Jan Kowalski</h3>
                <div class="email"><a href="mailto:j.kowalski@domo.com.pl">j.kowalski@domo.com.pl</a></div>
                <div class="desc">Oferty, zamówienia, adaptacje</div>
                <div class="phone">
                  <a href="tel:+48530234262"><i class="fa fa-phone"></i> 530 234 262</a>
                </div>
              </div>
            </div>
            <div class="contact-person">
              <img src="/img/avatars/avatar-jan.png" alt="" />
              <div class="contact-person-info">
                <h3>arch. tomasz<br />lewandowicz</h3>
                <div class="email"><a href="mailto:t.lewandowicz@domo.com.pl">t.lewandowicz@domo.com.pl</a></div>
                <div class="desc">Adaptacje projektów,<br />
                  usługi formalno-prawne</div>
                <div class="phone">
                  <a href="tel:+48731033433"><i class="fa fa-phone"></i> 731 033 433</a>
                </div>
              </div>
            </div>
            <div class="contact-person">
              <img src="/img/avatars/avatar-kasia.png" alt="" />
              <div class="contact-person-info">
                <h3>KATARZYNA WAŚ</h3>
                <div class="email"><a href="mailto:info@domo.com.pl">info@domo.com.pl</a></div>
                <div class="desc">Współpraca, marketing, finansowanie</div>
                <div class="phone">
                  <a href="tel:+48514007751"><i class="fa fa-phone"></i> 514 007 751</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-5 col-lg-4">
      <div class="sidebar-box">
        <div id="expert">
          <h1>
            Jan Kowalski
          </h1>
          <h2>
            Ekspert domo do twojej dyspozycji
          </h2>
          <div class="avatar">
            <img src="/img/avatars/avatar-jan-big.png" alt="" />
          </div>
          <div class="phone">
            <a href="tel:+48530234262"><i class="fa fa-phone"></i> +48 530 234 262</a>
          </div>
          <div class="email">
            <a href="mailto:jan.kowalski@email.pl"><i class="fa fa-envelope-o"></i> jan.kowalski@email.pl</a>
          </div>
        </div>
        <div id="office-open">
          <h2>Biuro jest czynne w godzinach:</h2>
          <h1>PON-PT 9<sup>00</sup> - 17<sup>00</sup></h1>
        </div>
        <div id="hotline">
          <h2>KONSULTANT DYŻURNY:</h2>
          <p>OD <strong>17:00</strong> DO <strong>19:00</strong><br />
            W DNI ROBOCZE I SOBOTY<br />
            <br />
            OD <strong>10:00</strong> DO <strong>14:00</strong> W NIEDZIELE</p>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-7 col-lg-8">
      <iframe id="google-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2440.7458978875147!2d21.071987216161638!3d52.28431497977035!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471ece8efdee57a3%3A0x67486c0148c8e07c!2sRadzymi%C5%84ska+230%2C+Warszawa!5e0!3m2!1spl!2spl!4v1519374880862" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
  </div>
</section>

<?php include('partials/footer.php'); ?>
<?php include('partials/foot.php'); ?>