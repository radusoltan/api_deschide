import {createAction, createSlice} from "@reduxjs/toolkit"
import {userLogin, userLogout} from "./authActions"

const userToken = localStorage.getItem("userToken") ?? null

const initialState = {
  loading: false,
  error: null,
  success: false,
}

const authSlice = createSlice({
  name: "auth",
  initialState,
  reducers: {
    logout: (state) => {
      localStorage.removeItem("userToken")
      localStorage.removeItem("userInfo")
      state.loading = false
      state.error = null
      state.success = false
    },
    setCredentials: (state, {payload}) => {
      localStorage.setItem('userInfo',JSON.stringify(payload))
    }
  },
  extraReducers: {
    [userLogin.pending]: state => {
      state.loading = true
      state.error = null
      state.success = false
    },
    [userLogin.fulfilled]: (state, {payload}) => {
      localStorage.setItem('userToken',payload.token)
      localStorage.setItem('userInfo',JSON.stringify(payload.user))
      state.loading = false
      state.error = null
      state.success = true
      window.location.href = "/"
    },
    [userLogin.rejected]: (state, {payload}) => {
      localStorage.removeItem("userToken")
      localStorage.removeItem("userInfo")
      state.loading = false
      state.error = payload
      state.success = false
      window.location.href = "/login"
    },
    [userLogout.pending]: state => {
      state.loading = true
      state.error = null
      state.success = false
      window.location.href = "/login"
    },
    [userLogout.fulfilled]: (state,{payload}) => {
      localStorage.removeItem("userToken")
      localStorage.removeItem("userInfo")
      state.loading = false
      state.error = null
      state.success = true

      window.location.href = "/login"
    },
    [userLogout.rejected]: (state, action) => {
      localStorage.removeItem("userToken")
      localStorage.removeItem("userInfo")

      window.location.href = "/login"
    }
  }
})

export const { logout, setCredentials} = authSlice.actions
export default authSlice.reducer
