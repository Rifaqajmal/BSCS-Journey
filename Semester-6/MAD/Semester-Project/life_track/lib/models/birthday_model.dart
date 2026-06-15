class BirthdayModel {
  final String id;
  final String name;
  final DateTime dob;
  final String relation;
  final String? photoUrl;

  BirthdayModel({
    required this.id,
    required this.name,
    required this.dob,
    required this.relation,
    this.photoUrl,
  });

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
      'dob': dob.toIso8601String(),
      'relation': relation,
      'photoUrl': photoUrl,
    };
  }

  factory BirthdayModel.fromMap(Map<dynamic, dynamic> map) {
    return BirthdayModel(
      id: map['id'] ?? '',
      name: map['name'] ?? '',
      dob: DateTime.parse(map['dob']),
      relation: map['relation'] ?? '',
      photoUrl: map['photoUrl'],
    );
  }
}