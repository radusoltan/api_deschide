import {useDispatch, useSelector} from "react-redux";
import {useGetUserDetailsQuery} from "../services/auth";
import {useEffect} from "react";
import {setCredentials} from "../features/auth/authSlice";
import {Navigate, useNavigate} from "react-router-dom";
import {MainLayout} from "./MainLayout";

export const Protected = () => {

  const userInfo = JSON.parse(localStorage.getItem('userInfo'))


  if (!userInfo){

    return <Navigate to="/login" />
  }

  return <MainLayout />
}
