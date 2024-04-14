import 'package:flutter/material.dart';

class FormSelect extends StatelessWidget {
  FormSelect({Key? key, required this.options, this.value, this.onChanged})
      : super(key: key);

  final List<DropdownMenuItem> options;
  final dynamic value;
  final Function(dynamic)? onChanged;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.symmetric(vertical: 12.0, horizontal: 16.0),
      decoration: BoxDecoration(
        color: Colors.white,
        border: Border.all(color: Colors.black.withOpacity(0.25), width: 1.0),
        borderRadius: BorderRadius.circular(4),
      ),
      child: DropdownButtonHideUnderline(
        child: DropdownButton(
          isExpanded: true,
          isDense: true,
          items: options,
          value: value,
          onChanged: onChanged,
        ),
      ),
    );
  }
}
