import { Eye, EyeOff } from "lucide-react"
import React, { useState } from "react"
import { useNavigate } from "react-router-dom"

const SignupPage: React.FC = () => {
  const [role, setRole] = useState<"Admin" | "Staff" | "User">("Admin")
  const [name, setName] = useState("")   // 👈 Added name field
  const [email, setEmail] = useState("")
  const [password, setPassword] = useState("")
  const [showPassword, setShowPassword] = useState(false)
  const navigate = useNavigate()

  const handleSignup = async (e: React.FormEvent) => {
    e.preventDefault()

    try {
      const response = await fetch("http://127.0.0.1:8000/api/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        credentials: "include",
        body: JSON.stringify({
          name,
          email,
          password,
          role,
        }),
      })

      const data = await response.json()

      if (response.ok) {
        alert("Signup successful!")
        navigate("/authentication/LoginPage")
      } else {
        alert(data.message || "Signup failed")
      }
    } catch (error) {
      console.error("Error:", error)
      alert("Something went wrong. Please try again.")
    }
  }

  return (
    <div className="flex items-center justify-center min-h-screen bg-blue-100">
      <div className="w-full max-w-2xl bg-white shadow-xl rounded-xl p-10">
        <h2 className="text-4xl font-bold text-center mb-2 text-blue-700">
          CivicFix SignUp
        </h2>
        <p className="text-center text-gray-500 mb-8 text-lg">
          Create your account
        </p>

        {/* Role Tabs */}
        <div className="flex justify-between mb-6 border rounded-lg overflow-hidden">
          {["Admin", "Staff", "User"].map((r) => (
            <button
              key={r}
              type="button"
              onClick={() => setRole(r as "Admin" | "Staff" | "User")}
              className={`flex-1 py-3 font-semibold text-lg transition ${
                role === r
                  ? "bg-blue-200 text-blue-800"
                  : "bg-gray-100 text-gray-600"
              }`}
            >
              {r}
            </button>
          ))}
        </div>

        <form onSubmit={handleSignup} className="space-y-6">
          {/* Name */}
          <input
            type="text"
            placeholder="Full Name"
            value={name}
            onChange={(e) => setName(e.target.value)}
            required
            className="w-full px-4 py-3 border rounded-md text-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
          />

          {/* Email */}
          <input
            type="email"
            placeholder="Email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
            className="w-full px-4 py-3 border rounded-md text-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
          />

          {/* Password with toggle */}
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

          {/* Submit */}
          <button
            className="w-full py-3 rounded-md text-white font-semibold text-lg
            bg-gradient-to-r from-blue-400 to-blue-700 hover:from-blue-500 hover:to-blue-800 transition"
          >
            Sign Up
          </button>
        </form>

        <div className="text-center mt-6 text-lg">
          Already have an account?{" "}
          <button
            onClick={() => navigate("/authentication/LoginPage")}
            className="text-blue-600 font-medium"
          >
            Login
          </button>
        </div>
      </div>
    </div>
  )
}

export default SignupPage
