<?php include('partials/head.php'); ?>
<?php
$desktop_header_class = "";
$is_home = false;
?>
<?php include('partials/desktop-header.php'); ?>
<?php include('partials/mobile-header.php'); ?>

<section id="category-list" class="container">
  <header class="row with-square">
    <h1 class="col-xs-12 default-headline default-headline-bigger">
      <a href="" class="button button-back button-medium button-to-right button-filled button-filled-gray">Wróć na główną</a>
      <span>PROJEKTY DOMÓW</span> PARTEROWYCH
      <small>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque eu interdum eros. Vestibulum rhoncus lacus ut pretium euismod.</small>
      </h1>
  </header>
  <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-4">
      <section class="sidebar-box">
        <header>
          <h2>Filtr</h2>
        </header>
        <div class="sidebar-box-content">
          <section id="filters">
            <div class="filters-row">
              <div class="input-holder input-holder-text">
                <label>Powierzchnia użytkowa:</label>
                <div class="inputs-group">
                  <input id="filters-surface-from" type="text" name="" placeholder="od" /> - <input id="filters-surface-to" type="text" name="" placeholder="do" /> m<sup>2</sup>
                </div>
              </div>
              <div class="input-holder input-holder-text">
                <label>Minimalne wymiary działki:</label>
                <div class="inputs-group">
                  <input id="filters-length" type="text" name="" placeholder="długość" /> x <input id="filters-width" type="text" name="" placeholder="szerokość" /> m
                </div>
              </div>
              <div class="input-holder input-holder-select">
                <select name="">
                  <option value="">Piwnica</option>
                  <option value="Opcja 1">Opcja 1</option>
                  <option value="Opcja 2">Opcja 2</option>
                </select>
              </div>
              <div class="input-holder input-holder-select">
                <label>Garaż:</label>
                <select name="">
                  <option value="">dowolny</option>
                  <option value="Opcja 1">Opcja 1</option>
                  <option value="Opcja 2">Opcja 2</option>
                </select>
              </div>
              <div class="input-holder input-holder-select">
                <select name="">
                  <option value="">Kondygnacja</option>
                  <option value="Opcja 1">Opcja 1</option>
                  <option value="Opcja 2">Opcja 2</option>
                </select>
              </div>
              <div class="input-holder input-holder-select">
                <select name="">
                  <option value="">Stylistyka</option>
                  <option value="Opcja 1">Opcja 1</option>
                  <option value="Opcja 2">Opcja 2</option>
                </select>
              </div>
              <div class="input-holder input-holder-range">
                <label>Wysokość budynku:</label>
                <input id="filter-range-1" name="" />
              </div>
              <div class="input-holder input-holder-select">
                <select name="">
                  <option value="">Rodzaj dachu</option>
                  <option value="Opcja 1">Opcja 1</option>
                  <option value="Opcja 2">Opcja 2</option>
                </select>
              </div>
              <div class="input-holder input-holder-range">
                <label>Kąt nachylenia dachu:</label>
                <input id="filter-range-2" name="" />
              </div>
              <div class="buttons-holder">
                <button id="filters-clear" class="button button-medium button-filled button-filled-gray">
                  Wyzeruj
                </button>
                <button id="filters-clear" class="button button-medium button-filled button-filled-yellow">
                  Więcej kryteriów
                </button>
              </div>
            </div>
          </section>
        </div>
      </section>
      <?php include('partials/checks.php'); ?>
    </div> 
    <div class="col-xs-12 col-sm-6 col-md-8">
      <ul class="row projects-items">
        <li class="col-xs-12 col-sm-12 col-md-6">
            <article class="project-item">
               <header>
                 <h2>Szyper 6 dr-s</h2>
               </header>
               <div class="content">
                 <div class="thumb" style="background-image: url(/img/temp/thumb-home-01.png)">
                   <span class="price">1235 PLN</span>
                 </div>
                 <div class="description">
                   <p>Powierzchnia: <strong>123m<sup>2</sup></strong></p>
                   <p>Parter + poddasze</p>
                   <p>Z garażem, dom tradycyjny</p>
                 </div>
              </div>
              <footer>
                <a href="">Więcej</a>
                <a href="" class="item-button item-button-add-to-closet" data-tooltip="Dodaj do schowka"></a>
                <a href="" class="item-button item-button-add-to-compare" data-tooltip="Dodaj do porównywarki"></a>
                <span class="item-button item-button-share" data-tooltip="Udostępnij"></span>
              </footer>
            </article>
        </li>
        <li class="col-xs-12 col-sm-12 col-md-6">
            <article class="project-item">
               <header>
                 <h2>Szyper 6 dr-s</h2>
               </header>
               <div class="content">
                 <div class="thumb" style="background-image: url(/img/temp/thumb-home-01.png)">
                   <span class="price">1235 PLN</span>
                 </div>
                 <div class="description">
                   <p>Powierzchnia: <strong>123m<sup>2</sup></strong></p>
                   <p>Parter + poddasze</p>
                   <p>Z garażem, dom tradycyjny</p>
                 </div>
              </div>
              <footer>
                <a href="">Więcej</a>
                <a href="" class="item-button item-button-add-to-closet" data-tooltip="Dodaj do schowka"></a>
                <a href="" class="item-button item-button-add-to-compare" data-tooltip="Dodaj do porównywarki"></a>
                <span class="item-button item-button-share" data-tooltip="Udostępnij"></span>
              </footer>
            </article>
        </li>
        <li class="col-xs-12 col-sm-12 col-md-6">
            <article class="project-item">
               <header>
                 <h2>Szyper 6 dr-s</h2>
               </header>
               <div class="content">
                 <div class="thumb" style="background-image: url(/img/temp/thumb-home-01.png)">
                   <span class="price">1235 PLN</span>
                 </div>
                 <div class="description">
                   <p>Powierzchnia: <strong>123m<sup>2</sup></strong></p>
                   <p>Parter + poddasze</p>
                   <p>Z garażem, dom tradycyjny</p>
                 </div>
              </div>
              <footer>
                <a href="">Więcej</a>
                <a href="" class="item-button item-button-add-to-closet" data-tooltip="Dodaj do schowka"></a>
                <a href="" class="item-button item-button-add-to-compare" data-tooltip="Dodaj do porównywarki"></a>
                <span class="item-button item-button-share" data-tooltip="Udostępnij"></span>
              </footer>
            </article>
        </li>
        <li class="col-xs-12 col-sm-12 col-md-6">
            <article class="project-item">
               <header>
                 <h2>Szyper 6 dr-s</h2>
               </header>
               <div class="content">
                 <div class="thumb" style="background-image: url(/img/temp/thumb-home-01.png)">
                   <span class="price">1235 PLN</span>
                 </div>
                 <div class="description">
                   <p>Powierzchnia: <strong>123m<sup>2</sup></strong></p>
                   <p>Parter + poddasze</p>
                   <p>Z garażem, dom tradycyjny</p>
                 </div>
              </div>
              <footer>
                <a href="">Więcej</a>
                <a href="" class="item-button item-button-add-to-closet" data-tooltip="Dodaj do schowka"></a>
                <a href="" class="item-button item-button-add-to-compare" data-tooltip="Dodaj do porównywarki"></a>
                <span class="item-button item-button-share" data-tooltip="Udostępnij"></span>
              </footer>
            </article>
        </li>
        <li class="col-xs-12 col-sm-12 col-md-6">
            <article class="project-item">
               <header>
                 <h2>Szyper 6 dr-s</h2>
               </header>
               <div class="content">
                 <div class="thumb" style="background-image: url(/img/temp/thumb-home-01.png)">
                   <span class="price">1235 PLN</span>
                 </div>
                 <div class="description">
                   <p>Powierzchnia: <strong>123m<sup>2</sup></strong></p>
                   <p>Parter + poddasze</p>
                   <p>Z garażem, dom tradycyjny</p>
                 </div>
              </div>
              <footer>
                <a href="">Więcej</a>
                <a href="" class="item-button item-button-add-to-closet" data-tooltip="Dodaj do schowka"></a>
                <a href="" class="item-button item-button-add-to-compare" data-tooltip="Dodaj do porównywarki"></a>
                <span class="item-button item-button-share" data-tooltip="Udostępnij"></span>
              </footer>
            </article>
        </li>
        <li class="col-xs-12 col-sm-12 col-md-6">
            <article class="project-item">
               <header>
                 <h2>Szyper 6 dr-s</h2>
               </header>
               <div class="content">
                 <div class="thumb" style="background-image: url(/img/temp/thumb-home-01.png)">
                   <span class="price">1235 PLN</span>
                 </div>
                 <div class="description">
                   <p>Powierzchnia: <strong>123m<sup>2</sup></strong></p>
                   <p>Parter + poddasze</p>
                   <p>Z garażem, dom tradycyjny</p>
                 </div>
              </div>
              <footer>
                <a href="">Więcej</a>
                <a href="" class="item-button item-button-add-to-closet" data-tooltip="Dodaj do schowka"></a>
                <a href="" class="item-button item-button-add-to-compare" data-tooltip="Dodaj do porównywarki"></a>
                <span class="item-button item-button-share" data-tooltip="Udostępnij"></span>
              </footer>
            </article>
        </li>
        <li class="col-xs-12 col-sm-12 col-md-6">
            <article class="project-item">
               <header>
                 <h2>Szyper 6 dr-s</h2>
               </header>
               <div class="content">
                 <div class="thumb" style="background-image: url(/img/temp/thumb-home-01.png)">
                   <span class="price">1235 PLN</span>
                 </div>
                 <div class="description">
                   <p>Powierzchnia: <strong>123m<sup>2</sup></strong></p>
                   <p>Parter + poddasze</p>
                   <p>Z garażem, dom tradycyjny</p>
                 </div>
              </div>
              <footer>
                <a href="">Więcej</a>
                <a href="" class="item-button item-button-add-to-closet" data-tooltip="Dodaj do schowka"></a>
                <a href="" class="item-button item-button-add-to-compare" data-tooltip="Dodaj do porównywarki"></a>
                <span class="item-button item-button-share" data-tooltip="Udostępnij"></span>
              </footer>
            </article>
        </li>
        <li class="col-xs-12 col-sm-12 col-md-6">
            <article class="project-item">
               <header>
                 <h2>Szyper 6 dr-s</h2>
               </header>
               <div class="content">
                 <div class="thumb" style="background-image: url(/img/temp/thumb-home-01.png)">
                   <span class="price">1235 PLN</span>
                 </div>
                 <div class="description">
                   <p>Powierzchnia: <strong>123m<sup>2</sup></strong></p>
                   <p>Parter + poddasze</p>
                   <p>Z garażem, dom tradycyjny</p>
                 </div>
              </div>
              <footer>
                <a href="">Więcej</a>
                <a href="" class="item-button item-button-add-to-closet" data-tooltip="Dodaj do schowka"></a>
                <a href="" class="item-button item-button-add-to-compare" data-tooltip="Dodaj do porównywarki"></a>
                <span class="item-button item-button-share" data-tooltip="Udostępnij"></span>
              </footer>
            </article>
        </li>
        <li class="col-xs-12 col-sm-12 col-md-6">
            <article class="project-item">
               <header>
                 <h2>Szyper 6 dr-s</h2>
               </header>
               <div class="content">
                 <div class="thumb" style="background-image: url(/img/temp/thumb-home-01.png)">
                   <span class="price">1235 PLN</span>
                 </div>
                 <div class="description">
                   <p>Powierzchnia: <strong>123m<sup>2</sup></strong></p>
                   <p>Parter + poddasze</p>
                   <p>Z garażem, dom tradycyjny</p>
                 </div>
              </div>
              <footer>
                <a href="">Więcej</a>
                <a href="" class="item-button item-button-add-to-closet" data-tooltip="Dodaj do schowka"></a>
                <a href="" class="item-button item-button-add-to-compare" data-tooltip="Dodaj do porównywarki"></a>
                <span class="item-button item-button-share" data-tooltip="Udostępnij"></span>
              </footer>
            </article>
        </li>
        <li class="col-xs-12 col-sm-12 col-md-6">
            <article class="project-item">
               <header>
                 <h2>Szyper 6 dr-s</h2>
               </header>
               <div class="content">
                 <div class="thumb" style="background-image: url(/img/temp/thumb-home-01.png)">
                   <span class="price">1235 PLN</span>
                 </div>
                 <div class="description">
                   <p>Powierzchnia: <strong>123m<sup>2</sup></strong></p>
                   <p>Parter + poddasze</p>
                   <p>Z garażem, dom tradycyjny</p>
                 </div>
              </div>
              <footer>
                <a href="">Więcej</a>
                <a href="" class="item-button item-button-add-to-closet" data-tooltip="Dodaj do schowka"></a>
                <a href="" class="item-button item-button-add-to-compare" data-tooltip="Dodaj do porównywarki"></a>
                <span class="item-button item-button-share" data-tooltip="Udostępnij"></span>
              </footer>
            </article>
        </li>
      </ul>
      <ul id="pagination">
        <li class="prev"><a href=""></a></li>
        <li class="page current"><a href="">1</a></li>
        <li class="page"><a href="">2</a></li>
        <li class="page"><a href="">3</a></li>
        <li class="page"><a href="">4</a></li>
        <li class="page"><a href="">5</a></li>
        <li class="next"><a href=""></a></li>
      </ul>
    </div> 
  </div>
</section>

<?php include('partials/footer.php'); ?>
<?php include('partials/foot.php'); ?>