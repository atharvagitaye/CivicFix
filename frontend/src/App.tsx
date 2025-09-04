import React from "react"
import { Routes, Route } from "react-router-dom"
import SuperAdminLogin from "./pages/superadmin/Login"

function App() {
  return (
    <Routes>
      {/* Home route */}
      <Route path="/" element={<h1>Welcome to CivicFix</h1>} />

      {/* Super Admin Login */}
      <Route path="/superadmin/Login" element={<SuperAdminLogin />} />

      {/* Later you can add: /superadmin/dashboard, /resident/report, etc. */}
    </Routes>
  )
}

export default App
