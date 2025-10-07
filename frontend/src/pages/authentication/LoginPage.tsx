import React, { useState } from "react"
import { useNavigate } from "react-router-dom"
import { Eye, EyeOff } from "lucide-react"   // 👈 eye icons

const LoginPage: React.FC = () => {
  const [role, setRole] = useState<"Admin" | "Staff" | "User">("Admin")
  const [email, setEmail] = useState("")
  const [password, setPassword] = useState("")
  const [showPassword, setShowPassword] = useState(false) // 👈 toggle state
  const navigate = useNavigate()

  const handleLogin = (e: React.FormEvent) => {
    e.preventDefault()

    // Redirect based on role after login
    if (role === "Admin") {
      navigate("/superadmin/Dashboard")
    } else if (role === "Staff") {
      navigate("/Staff/StaffDashboard")
    } else {
      navigate("/Users/UserDashboard")
    }
  }

  return (
    <div className="flex items-center justify-center min-h-screen bg-blue-100">
      {/* Bigger card */}
      <div className="w-full max-w-2xl bg-white shadow-xl rounded-xl p-10">
        <h2 className="text-4xl font-bold text-center mb-2 text-blue-700">CivicFix Portal</h2>
        <p className="text-center text-gray-500 mb-8 text-lg">Sign in to your account</p>

        {/* Role Tabs */}
        <div className="flex justify-between mb-8 border rounded-lg overflow-hidden">
          {["Admin", "Staff", "User"].map((r) => (
            <button
              key={r}
              onClick={() => setRole(r as "Admin" | "Staff" | "User")}
              className={`flex-1 py-3 font-semibold text-lg transition ${
                role === r ? "bg-blue-200 text-blue-800" : "bg-gray-100 text-gray-600"
              }`}
            >
              {r}
            </button>
          ))}
        </div>

        {/* Form */}
        <form onSubmit={handleLogin} className="space-y-6">
          {/* Email */}
          <input
            type="email"
            placeholder="Email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            className="w-full px-4 py-3 border rounded-md text-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
          />

          {/* Password with eye toggle */}
          <div className="relative">
            <input
              type={showPassword ? "text" : "password"}
              placeholder="Password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              className="w-full px-4 py-3 border rounded-md text-lg focus:outline-none focus:ring-2 focus:ring-blue-400 pr-12"
            />
            <button
              type="button"
              onClick={() => setShowPassword(!showPassword)}
              className="absolute inset-y-0 right-4 flex items-center text-gray-500 hover:text-gray-700"
            >
              {showPassword ? <EyeOff size={22} /> : <Eye size={22} />}
            </button>
          </div>

          {/* Submit Button */}
          <button
            className="w-full py-3 rounded-md text-white text-lg font-semibold 
              bg-gradient-to-r from-blue-400 to-blue-700 
              hover:from-blue-500 hover:to-blue-800 
              transition"
          >
            Sign In
          </button>
        </form>

        {/* Links */}
        <div className="flex justify-between mt-6 text-md text-blue-600 font-medium">
          <button onClick={() => navigate("/authentication/ForgotPasswordPage")}>
            Forgot Password?
          </button>
          <button onClick={() => navigate("/authentication/SignupPage")}>
            Sign Up
          </button>
        </div>
      </div>
    </div>
  )
}

export default LoginPage
