  <section class="container my-5">
      <div class="row justify-content-center">
          <div class="col-12 col-lg-10 caixa-promocao p-3 p-md-4 rounded-4 shadow-lg">
              <?php if (!empty($featured)): ?>
                  <div id="carouselPromocoes" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">

                      <div class="carousel-indicators mb-n4">
                          <?php foreach ($featured as $idx => $product): ?>
                              <button type="button" data-bs-target="#carouselPromocoes" data-bs-slide-to="<?= $idx ?>"
                                  class="<?= $idx === 0 ? 'active' : '' ?>"
                                  aria-current="<?= $idx === 0 ? 'true' : 'false' ?>"></button>
                          <?php endforeach; ?>
                      </div>

                      <div class="carousel-inner">
                          <?php foreach ($featured as $idx => $product): ?>
                              <div class="carousel-item <?= $idx === 0 ? 'active' : '' ?>">
                                  <div class="card border-0 rounded-4 overflow-hidden h-100 position-relative" style="min-height: 400px">
                                      <div class="row g-0 h-100">
                                          <div class="col-md-6 bg-light d-flex align-items-center justify-content-center p-4 card-img-container">
                                              <a href="/produto?id=<?= $product->id ?>" class="d-flex justify-content-center w-100 h-100 align-items-center text-decoration-none">
                                                  <img src="<?= htmlspecialchars($product->imageUrl) ?>" class="img-fluid card-product-img" style="max-height: 250px; object-fit: contain" alt="<?= htmlspecialchars($product->name) ?>" />
                                              </a>
                                          </div>
                                          <div class="col-md-6 d-flex align-items-center">
                                              <div class="card-body p-4 p-lg-5 text-center text-md-start">
                                                  <?php if ($product->getDiscountLabel()): ?>
                                                      <div class="mb-2">
                                                          <span class="badge bg-danger">OFERTA ESPECIAL <?= $product->getDiscountLabel() ?></span>
                                                      </div>
                                                  <?php endif; ?>

                                                  <h3 class="card-title fw-bold text-primary mb-3"><?= htmlspecialchars($product->name) ?></h3>
                                                  <p class="card-text text-secondary fs-5 mb-4"><?= htmlspecialchars($product->description) ?></p>

                                                  <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                                                      <span class="badge bg-warning text-dark fs-5 px-3 py-2 rounded-pill shadow-sm">
                                                          <?= $product->getFormattedCashPrice() ?> <small class="fw-normal">à vista</small>
                                                      </span>
                                                      <a href="/produto?id=<?= $product->id ?>" class="btn btn-primary rounded-pill px-4 fw-bold stretched-link">Comprar Agora</a>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          <?php endforeach; ?>
                      </div>

                      <button class="carousel-control-prev carousel-dark" type="button" data-bs-target="#carouselPromocoes" data-bs-slide="prev">
                          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Anterior</span>
                      </button>
                      <button class="carousel-control-next carousel-dark" type="button" data-bs-target="#carouselPromocoes" data-bs-slide="next">
                          <span class="carousel-control-next-icon" aria-hidden="true"></span>
                          <span class="visually-hidden">Próximo</span>
                      </button>
                  </div>
              <?php else: ?>
                  <div class="text-center text-white py-5">
                      <h3>Nenhuma promoção ativa no momento.</h3>
                  </div>
              <?php endif; ?>
          </div>
      </div>
  </section>
