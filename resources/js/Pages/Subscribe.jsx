import { useForm } from '@inertiajs/react';

export default function Subscribe() {
  const { data, setData, post, processing, errors, reset } = useForm({
    email: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    post('/subscribe', {
      onSuccess: () => {
        alert('Subscribed successfully!');
        reset();
      },
    });
  };

  return (
    <div className="p-6 max-w-md mx-auto bg-white rounded shadow">
      <h2 className="text-xl font-semibold mb-4">Subscribe to Newsletter</h2>
      <form onSubmit={handleSubmit}>
        <input
          type="email"
          value={data.email}
          onChange={(e) => setData('email', e.target.value)}
          className="border p-2 w-full mb-2"
          placeholder="Enter your email"
        />
        {errors.email && <div className="text-red-500 mb-2">{errors.email}</div>}
        <button
          type="submit"
          disabled={processing}
          className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
        >
          Subscribe
        </button>
      </form>
    </div>
  );
}
