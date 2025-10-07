import React from "react";

const Footer: React.FC = () => {
  return (
    <footer className="bg-gray-800 text-gray-300 text-center py-4 mt-8">
      <p className="text-sm">
        © {new Date().getFullYear()} My Dashboard. All rights reserved.
      </p>
    </footer>
  );
};

export default Footer;
