import React from 'react';

const ProductCard = ({ product }) => {
  return (
    <div className="col-12 col-sm-6 col-md-4 col-lg-3">
      <div className="card h-100 shadow-sm border-0">
        <div className="d-flex justify-content-center align-items-center p-3" style={{ height: '250px' }}>
          <img src={product.image} className="card-img-top mw-100 mh-100" style={{ objectFit: 'contain' }} alt={product.name} />
        </div>
        <div className="card-body d-flex flex-column">
          <h5 className="card-title fw-bold text-dark">{product.name}</h5>
          <p className="card-text small text-secondary">{product.description}</p>
          <div className="mt-auto">
            <p className="card-text fw-bold fs-5 text-primary">
              {new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(product.price)}
            </p>
            <a href="/" className="btn btn-primary w-100 rounded-pill">Comprar</a>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductCard;
