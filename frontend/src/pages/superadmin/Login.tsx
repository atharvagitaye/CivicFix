// components/ui/SuperAdminLogin.tsx
import React, { useState } from "react"
import { Button } from "@/components/ui/button"
import "./Login.css"

const SuperAdminLogin: React.FC = () => {
  const [username, setUsername] = useState("")
  const [password, setPassword] = useState("")
  const [error, setError] = useState("")

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    if (!username || !password) {
      setError("Username and password are required.")
      return
    }

    try {
      const response = await fetch("/api/superadmin/Login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, password }),
      })

      if (response.ok) {
        const data = await response.json()
        localStorage.setItem("token", data.token)
        window.location.href = "/superadmin/dashboard"
      } else {
        setError("Invalid credentials. Please try again.")
      }
    } catch (err) {
      setError("Server error. Please try again later.")
    }
  }

  return (
    <div className="superadmin-login-page">
      <div className="superadmin-login-card">
        <h2 className="superadmin-login-title">Super Admin Login</h2>

        {error && <p className="superadmin-login-error">{error}</p>}

        <form onSubmit={handleSubmit}>
          <div className="superadmin-form-group">
            <label className="superadmin-label">Username</label>
            <input
              type="text"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              className="superadmin-input"
              placeholder="Enter username"
              required
            />
          </div>

          <div className="superadmin-form-group">
            <label className="superadmin-label">Password</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="superadmin-input"
              placeholder="Enter password"
              required
            />
          </div>

          <div className="superadmin-button-wrapper">
            <Button type="submit" className="w-full">
              Login
            </Button>
          </div>
        </form>
      </div>
    </div>
  )
}

export default SuperAdminLogin


