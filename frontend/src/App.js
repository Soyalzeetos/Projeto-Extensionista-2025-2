import React from "react";
import Navbar from "./components/Navbar";
import Footer from "./components/Footer";
import Home from "./pages/Home";

function App() {
  return (
    <div className="d-flex flex-column min-vh-100">
      <Navbar />
      <Home />
      <Footer />
    </div>
  );
}

export default App;
