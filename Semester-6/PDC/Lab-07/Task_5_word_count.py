from mpi4py import MPI

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

sentences = [
    "parallel computing is powerful",
    "mpi enables distributed systems",
    "python supports mpi programming",
    "collective communication is efficient"
]

text = sentences[rank]

word_count = len(text.split())

total_words = comm.reduce(word_count, op=MPI.SUM, root=0)

print("Process", rank, "word count:", word_count)

if rank == 0:
    print("Total words in all sentences:", total_words)