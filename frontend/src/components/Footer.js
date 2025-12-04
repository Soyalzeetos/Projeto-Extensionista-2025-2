import React from 'react';

const Footer = () => {
  return (
    <footer className="bg-dark text-white text-center py-4 mt-auto">
      <div className="container">
        <p className="mb-2 fw-bold">Center Ferramentas &copy; 2025</p>
        <p className="small text-white-50 mb-0">
          Rua dos Bobos, 000 - Centro | (34) 99999-9999
        </p>
        <div className="mt-3">
          <a href="https://www.instagram.com/centerferramentasfrutal/" className="text-white mx-2" target="_blank" rel="noopener noreferrer">
            <i className="fa-brands fa-instagram fa-lg"></i>
          </a>
          <a href="https://wa.me/5534991975188" className="text-white mx-2" target="_blank" rel="noopener noreferrer">
            <i className="fa-brands fa-whatsapp fa-lg"></i>
          </a>
          <a href="https://www.facebook.com/people/Centeer-Ferramentas/pfbid035G3rq1SRbJfu3YWsh7Cy8Q7kCkFfnFr2DzCszxQcduFVMxRNZ2SYbhy4wcBKQYGdl/" className="text-white mx-2" target="_blank" rel="noopener noreferrer">
            <i className="fa-brands fa-facebook fa-lg"></i>
          </a>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
