import React, { useEffect, useState } from "react";
import axios from "axios";
import ProductCard from "../components/ProductCard";

const Home = () => {
  const [products, setProducts] = useState([]);
  const [promotions, setPromotions] = useState([]);

  const apiUrl = process.env.REACT_APP_API_URL;

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        const response = await axios.get(`${apiUrl}/api/products.php`);
        const allProducts = response.data;
        setProducts(allProducts);
        setPromotions(allProducts.filter((p) => p.is_promo));
      } catch (error) {
        console.error("Erro ao buscar produtos:", error);
      }
    };
    fetchProducts();
  }, []);

  return (
    <main className="flex-grow-1">
      <div className="container-fluid marquee text-nowrap py-2 overflow-hidden border-bottom border-dark border-2">
        <div className="marquee-track d-flex">
          <div className="marquee-group d-flex gap-5 pe-5" aria-hidden="true">
            {[...Array(5)].map((_, i) => (
              <span key={i} className="fw-bold text-uppercase">
                🛠️ CENTER FERRAMENTAS - QUALIDADE, FORÇA E CONFIANÇA PRA SUA
                OBRA! 🔧 GRANDES OFERTAS TODOS OS DIAS! 💥
              </span>
            ))}
          </div>
        </div>
      </div>

      <section className="container my-5">
        <div className="row justify-content-center">
          <div className="col-12 col-lg-10 caixa-promocao p-3 p-md-4 rounded-4 shadow-lg">
            <div
              id="carouselPromocoes"
              className="carousel slide"
              data-bs-ride="carousel"
              data-bs-interval="4000"
            >
              <div className="carousel-inner">
                {promotions.map((promo, index) => (
                  <div
                    key={promo.id}
                    className={`carousel-item ${index === 0 ? "active" : ""}`}
                  >
                    <div
                      className="card border-0 rounded-4 overflow-hidden h-100 position-relative"
                      style={{ minHeight: "400px" }}
                    >
                      <div className="row g-0 h-100">
                        <div className="col-md-6 bg-light d-flex align-items-center justify-content-center p-4 card-img-container">
                          <img
                            src={promo.image}
                            className="img-fluid card-product-img"
                            style={{ maxHeight: "250px", objectFit: "contain" }}
                            alt={promo.name}
                          />
                        </div>
                        <div className="col-md-6 d-flex align-items-center">
                          <div className="card-body p-4 p-lg-5 text-center text-md-start">
                            <h3 className="card-title fw-bold text-primary mb-3">
                              {promo.name}
                            </h3>
                            <p className="card-text text-secondary fs-5 mb-4">
                              {promo.description}
                            </p>
                            <div className="d-flex flex-column flex-md-row align-items-center gap-3">
                              <span className="badge bg-warning text-dark fs-5 px-3 py-2 rounded-pill shadow-sm">
                                {new Intl.NumberFormat("pt-BR", {
                                  style: "currency",
                                  currency: "BRL",
                                }).format(promo.price)}
                              </span>
                              <button className="btn btn-primary rounded-pill px-4 fw-bold stretched-link">
                                Comprar Agora
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
              <button
                className="carousel-control-prev carousel-dark"
                type="button"
                data-bs-target="#carouselPromocoes"
                data-bs-slide="prev"
              >
                <span
                  className="carousel-control-prev-icon"
                  aria-hidden="true"
                ></span>
                <span className="visually-hidden">Anterior</span>
              </button>
              <button
                className="carousel-control-next carousel-dark"
                type="button"
                data-bs-target="#carouselPromocoes"
                data-bs-slide="next"
              >
                <span
                  className="carousel-control-next-icon"
                  aria-hidden="true"
                ></span>
                <span className="visually-hidden">Próximo</span>
              </button>
            </div>
          </div>
        </div>
      </section>

      <section className="secao-vendas container mb-5 my-5 rounded-4 g-5">
        <h2 className="pt-3 mb-4 texto-secao">Nossos produtos</h2>
        <div className="container-fluid row g-4 container-produtos">
          {products.map((product) => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
      </section>
    </main>
  );
};

export default Home;
