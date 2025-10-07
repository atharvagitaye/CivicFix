import React, { useState, useRef, useEffect } from "react";
import { Link } from "react-router-dom";

const Header: React.FC = () => {
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef<HTMLDivElement>(null);

  // Close the dropdown if clicked outside
  useEffect(() => {
    const handleClickOutside = (event: MouseEvent) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
        setIsOpen(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  return (
    <header className="bg-gray-800 text-white shadow-md w-full">
      <div className="w-full flex justify-between items-center py-4 px-4">
        {/* Logo - leftmost */}
        <h1 className="text-4xl font-bold text-blue-400">CivicFix</h1>

        {/* Right Side */}
        <div className="flex text-2xl items-center space-x-10">
          {/* Navigation links */}
          <nav className="flex space-x-10">
            <Link to="/Users/issues" className="hover:text-blue-400 font-medium">
              User Dashboard
            </Link>
            <Link to="/Dashboard" className="hover:text-blue-400 font-medium">
              Admin Dashboard
            </Link>
            <Link to="/Staff/Staffissues" className="hover:text-blue-400 font-medium">
              Staff Dashboard
            </Link>
            <Link
              to="/Authentication/LoginPage"
              className="text-red-400 font-semibold hover:text-red-500"
            >
              Logout
            </Link>
          </nav>

          {/* Profile Circle with Dropdown */}
          <div className="relative" ref={dropdownRef}>
            <div
              onClick={() => setIsOpen(!isOpen)}
              className="w-10 h-10 flex items-center justify-center rounded-full bg-blue-600 font-bold text-white cursor-pointer"
            >
              A
            </div>

            {/* Dropdown Card */}
            {isOpen && (
              <div className="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg z-50">
                <div className="p-4 border-b">
                  <p className="font-semibold">Ankita Pawar</p>
                  <p className="text-sm text-gray-500">ankita@example.com</p>
                </div>
                <ul>
                  <li>
                    <Link
                      to="/authentication/profile"
                      className="block px-4 py-2 hover:bg-gray-100"
                    >
                      View Profile
                    </Link>
                  </li>
                  <li>
                    <Link
                      to="/settings"
                      className="block px-4 py-2 hover:bg-gray-100"
                    >
                      Settings
                    </Link>
                  </li>
                  <li>
                    <Link
                      to="/Authentication/LoginPage"
                      className="block px-4 py-2 text-red-500 hover:bg-gray-100"
                    >
                      Logout
                    </Link>
                  </li>
                </ul>
              </div>
            )}
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;
