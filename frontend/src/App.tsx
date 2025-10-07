
import React from "react"
import { Routes, Route } from "react-router-dom"
import SuperAdminDashboard from "./pages/superadmin/Dashboard"
import StaffDashboard from "./pages/Staff/StaffDashboard"
import LoginPage from "./pages/authentication/LoginPage"
import SignupPage from "./pages/authentication/SignupPage"
import UserDashboard from "./pages/Users/UserDashboard"
import ForgotPasswordPage from "./pages/authentication/ForgotPassword"
import Footer from "./components/ui/footer"
import Header from "./components/ui/header"
import Issues from "./pages/Users/issues"
import StaffIssues from "./pages/Staff/Staffissues"
import Profile from "./pages/authentication/profile"
function App() {
  return (
    <Routes>
      {/* Home route */}
      <Route path="/" element={<h1>Welcome to CivicFix</h1>} />

      {/* Super Admin Login */}
    
      <Route path="/superadmin/dashboard" element={<SuperAdminDashboard />} />
      <Route path="/Staff/StaffDashboard" element={<StaffDashboard />} />
      <Route path="/Users/UserDashboard" element={<UserDashboard />} />
      <Route path="/authentication/LoginPage" element={<LoginPage />} />
      <Route path="/authentication/SignupPage" element={<SignupPage />} />
      <Route path="/authentication/ForgotPasswordPage" element={<ForgotPasswordPage />} />
      <Route path="/components/ui/header" element={<Header />} />
      <Route path="/components/ui/footer" element={<Footer />} />
      <Route path="/Users/issues" element={<Issues />} />
      <Route path="/Staff/Staffissues" element={<StaffIssues />} />
      <Route path="/authentication/profile" element={<Profile />} />
      {/* Later you can add: /superadmin/dashboard, /resident/report, etc. */}
    </Routes>
  )
}

export default App
