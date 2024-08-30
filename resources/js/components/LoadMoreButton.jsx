import React from 'react';

const LoadMoreButton = ({ click, show }) => {
    if (!show) {
        return;
    }
    return (
        <div className="px-4 py-4 flex justify-center">
            <button className="button" onClick={click}>
                Load More
            </button>
        </div>
    );
};

export default LoadMoreButton;
