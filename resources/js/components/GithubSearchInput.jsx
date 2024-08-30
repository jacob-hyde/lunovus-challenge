import React from 'react';

const SearchInput = ({ userInput, setUserInput }) => {
    return (
        <input
            type="text"
            placeholder="Github Username"
            className="input"
            value={userInput}
            onChange={(e) => setUserInput(e.target.value)}
        />
    );
};

export default SearchInput;
