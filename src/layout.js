import React, { Children } from "react";
import Header from "./Header";
function Layout({children}){
    return(
            <div>
                {/* Properties   */}
                <Header/>
                {children}
            </div>
    )
}
export default Layout;