import React from 'react';

const LoadingBar = ({ loading }) => {
    return (
        loading && (
            <div className="loading-bar">
                <div className="bar"></div>
            </div>
        )
    );
};

export default LoadingBar;
