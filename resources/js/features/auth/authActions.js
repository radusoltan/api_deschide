import axios from "../../lib/axios"
import {createAsyncThunk} from "@reduxjs/toolkit"

const backendUrl = process.env.MIX_BACKEND_URL

export const userLogin = createAsyncThunk(
    "auth/login",
    async ({email, password, remember}, {rejectWithValue}) => {
      try {
        const response = await axios.post(`/login`, {
          email,
          password,
          remember
        })

        return response.data
      } catch (e) {
        if (e.response && e.response.data){
          return rejectWithValue(e.response.data.message)
        } else {
          return rejectWithValue(e.message)
        }
      }
    }
)

export const userLogout = createAsyncThunk(
    "auth/logout",
  async ({},{rejectWithValue}) => {
      try {
        const response = await axios.post(`/logout`, {},{
          headers: {
            "Authorization": `Bearer ${localStorage.getItem("userToken")}`
          }
        })

        if (response.status === 204){
          return {
            success: true
          }
        }
      } catch (e) {
        return rejectWithValue(e.response)
      }
  }
)
