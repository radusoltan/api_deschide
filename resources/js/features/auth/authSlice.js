import {createSlice} from "@reduxjs/toolkit"
import {userLogin, userLogout} from "./authActions"

const userToken = localStorage.getItem("userToken") ?? null

const initialState = {
  loading: false,
  userInfo: null,
  userToken: userToken,
  error: null,
  success: false
}

const authSlice = createSlice({
  name: "auth",
  initialState,
  reducers: {
    logout: (state) => {
      localStorage.removeItem("userToken")
      localStorage.removeItem("userInfo")
      state.loading = false
      state.userInfo = null
      state.userToken = null
      state.error = null
      state.success = false
    },
    setCredentials: (state, {payload}) => {
      localStorage.setItem('userToken',payload.token)
      localStorage.setItem('userInfo',JSON.stringify(payload.user))
    }
  },
  extraReducers: {
    // userLogin
    [userLogin.pending]: (state) => {
      state.loading = true
      state.error = null
    },
    [userLogin.fulfilled]: (state, {payload}) => {
      localStorage.setItem("userToken", payload.token)
      localStorage.setItem("userInfo", JSON.stringify(payload.user))
      state.loading = false
      state.success = true
    },
    [userLogin.rejected]: (state, {payload}) => {
      state.loading = false
      state.error = payload
    }
  }
})

export const {logout,setCredentials} = authSlice.actions
export default authSlice.reducer
