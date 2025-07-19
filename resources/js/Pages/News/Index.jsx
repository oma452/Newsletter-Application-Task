import React from 'react';
import { Head } from '@inertiajs/react';

export default function Index({ news }) {
  return (
    <div className="p-6">
      <Head title="Latest News" />
      <h1 className="text-2xl font-bold mb-4">📰 Latest News</h1>


        {news.length === 0 ? (
        <p>No news articles available.</p>
      ) : (
        <ul className="space-y-6">
          {news.map((article) => (
            <li key={article.url} className="bg-white shadow p-4 rounded">
              {article.urlToImage  && (
                <img
                  src={article.urlToImage}
                  alt={article.title}
                  className="w-full h-48 object-cover mb-2 rounded"
                />
              )}
              <h2 className="text-lg font-semibold">{article.title}</h2>
              <p>{article.content}</p>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
