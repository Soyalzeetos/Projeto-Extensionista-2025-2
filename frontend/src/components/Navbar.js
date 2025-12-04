import React from "react";

const Navbar = () => {
  const apiUrl = process.env.REACT_APP_API_URL;

  return (
    <header>
      <nav className="navbar-custom container-fluid py-3">
        <div className="row align-items-center">
          <div className="col-3 col-lg-2 text-center">
            <a href="/" aria-label="Home">
              <img
                className="logo img-fluid"
                src={`${apiUrl}/assets/img/ui/logo.webp`}
                alt="Logo Center Ferramentas"
              />
            </a>
          </div>

          <div className="col-6 col-lg-8">
            <form role="search" className="search-class">
              <label htmlFor="campo-busca" className="visually-hidden">
                Buscar
              </label>
              <div className="input-group">
                <input
                  className="form-control border-0 rounded-start-pill py-2 ps-4"
                  type="search"
                  id="campo-busca"
                  placeholder="O que você precisa para sua obra hoje?"
                />
                <button
                  className="btn btn-light border-0 rounded-end-pill pe-4"
                  type="button"
                >
                  <i className="fa-solid fa-magnifying-glass text-secondary"></i>
                </button>
              </div>
            </form>
          </div>

          <div className="col-3 col-lg-2 d-flex justify-content-center gap-3">
            <button
              className="btn border-0 p-0 position-relative"
              aria-label="Carrinho"
            >
              <img
                className="icon-nav"
                src={`${apiUrl}/assets/img/ui/icone-carrinho.webp`}
                alt="Carrinho"
                aria-hidden="true"
              />
              <span className="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                <span className="visually-hidden">Novos itens</span>
              </span>
            </button>
            <button className="btn border-0 p-0" aria-label="Perfil">
              <img
                className="icon-nav"
                src={`${apiUrl}/assets/img/ui/icone-perfil.webp`}
                alt="Perfil"
                aria-hidden="true"
              />
            </button>
          </div>

          <div className="col-lg-12 d-flex justify-content-center gap-3 mt-3">
            <div className="d-flex gap-3">
              <div className="dropdown">
                <button className="dropbtn">
                  Categorias <i className="fa fa-caret-down"></i>
                </button>
                <div className="dropdown-content">
                  <a href="/" className="text-center">
                    Ferramentas Elétricas
                  </a>
                  <a href="/" className="text-center">
                    Ferramentas Manuais
                  </a>
                  <a href="/" className="text-center">
                    Material Elétrico
                  </a>
                  <a href="/" className="text-center">
                    Hidráulica
                  </a>
                  <a href="/" className="text-center">
                    Pintura e Acessórios
                  </a>
                  <a href="/" className="text-center">
                    Jardinagem e Pulverização
                  </a>
                  <a href="/" className="text-center">
                    EPIs e Segurança
                  </a>
                  <a href="/" className="text-center">
                    Parafusos e Fixadores
                  </a>
                  <a href="/" className="text-center">
                    Iluminação
                  </a>
                  <a href="/" className="text-center">
                    Adesivos e Selantes
                  </a>
                </div>
              </div>

              <div className="dropdown">
                <button className="dropbtn">
                  Aluguel <i className="fa fa-caret-down"></i>
                </button>
                <div className="dropdown-content">
                  <a href="/" className="text-center">
                    Andaimes e Escadas
                  </a>
                  <a href="/" className="text-center">
                    Betoneiras e Misturadores
                  </a>
                  <a href="/" className="text-center">
                    Compactadores de Solo
                  </a>
                  <a href="/" className="text-center">
                    Geradores e Compressores
                  </a>
                  <a href="/" className="text-center">
                    Marteletes e Rompedores
                  </a>
                  <a href="/" className="text-center">
                    Limpeza e Jardinagem
                  </a>
                </div>
              </div>

              <div className="dropdown">
                <button className="dropbtn">
                  Orçamentos <i className="fa fa-caret-down"></i>
                </button>
                <div className="dropdown-content">
                  <a href="/" className="text-center">
                    Cotação Rápida Online
                  </a>
                  <a href="/" className="text-center">
                    Vendas Corporativas (Atacado)
                  </a>
                  <a href="/" className="text-center">
                    Enviar Lista de Material
                  </a>
                  <a href="/" className="text-center">
                    Manutenção e Assistência
                  </a>
                  <a href="/" className="text-center">
                    Falar via WhatsApp
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </header>
  );
};

export default Navbar;
