import React, { useState } from "react";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

const ForgotPasswordPage: React.FC = () => {
  const [email, setEmail] = useState("");

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    console.log("Password reset request for:", email);
    alert("Password reset instructions sent to your email.");
  };

  return (
    <div className="flex justify-center items-center min-h-screen bg-blue-100">
      <Card className="w-full max-w-2xl shadow-xl rounded-xl p-10">
        <CardHeader>
          <CardTitle className="text-4xl font-bold text-center mb-2 text-blue-700">
            Forgot Password - CivicFix
          </CardTitle>
        </CardHeader>
        <CardContent>
          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
             <Label
                htmlFor="email"
                className="text-xl font-medium text-gray-700 mb-3 block"
              >
                Enter your email
              </Label>

              <Input 
                id="email"
                type="email"
                name="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="Enter your registered email"
                required
                className="py-3 px-4 text-xl"
              />
            </div>

            <button className="w-full py-2 rounded-md text-white font-xl-semibold 
  bg-gradient-to-r from-blue-400 to-blue-700 hover:from-blue-500 hover:to-blue-800 transition">
 Send Reset Link
</button>

            <p className="text-center text-lg text-gray-500 mt-3">
              Remember your password?{" "}
              <a href="/authentication/LoginPage" className="text-blue-600 hover:underline">
                Back to Login
              </a>
            </p>
          </form>
        </CardContent>
      </Card>
    </div>
  );
};

export default ForgotPasswordPage;
