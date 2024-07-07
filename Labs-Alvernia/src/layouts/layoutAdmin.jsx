import React from "react";
import { Outlet } from "react-router-dom";
import SidebarAdmin from "../components/SideBarAdmin";
import Header from "../components/Header";

const LayoutAdmin = () => {
    return (
        <div className="min-h-screen grid grid-cols-1 xl:grid-cols-6">
            <SidebarAdmin />
            <div className="xl:col-span-5">
                <Header />
                <div className="h-[90vh] overflow-y-scroll p-8">
                    <Outlet />
                </div>
            </div>
        </div>
    );
};

export default LayoutAdmin;